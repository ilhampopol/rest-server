<?php
class PaperlessAPI_model extends CI_Model
{
   private $_server;

   public function __construct()
   {
      $this->_server = $this->load->database('rafindo_dashboard', true);
   }

   public function getDepartment()
   {
      $query = 'SELECT dept_id,dept_desc FROM paper_dept';

      $data = $this->_server->query($query)->result_array();

      return $data;
   }

   public function getForm($sort)
   {
      $query = "SELECT form_id FROM paper_form WHERE SUBSTRING(form_id,8,6)='$sort' ORDER BY form_id DESC LIMIT 1";
      $data = $this->_server->query($query)->row_array();

      if ($data == null) {
         $data = 0;
         return $data;
      } else {
         return $data['form_id'];
      }
   }

   public function getAllForm()
   {
      $query =
         "SELECT d.description,f.id,f.desc_id,f.form_id,f.keperluan,f.dana,f.date_required,
               pf.lampiran1,pf.lampiran2,pf.lampiran3,pf.file_type1,pf.file_type2,pf.file_type3,f.created_by
               FROM paper_desc AS d
               JOIN paper_form AS f ON d.desc_id = f.desc_id
               JOIN paper_file AS pf ON f.form_id = pf.form_id";

      $data = $this->_server->query($query)->result_array();

      return $data;
   }

   public function getFormById($id)
   {
      $query = "SELECT f.id,d.dept_desc,f.form_id,f.desc_id,f.next_dept,f.keperluan,f.dana,f.date_required,
               pf.lampiran1,pf.lampiran2,pf.lampiran3,f.created_by,f.checked_by1,f.checked_by2,f.known_by,f.approved_by,f.paid_by
               FROM paper_dept AS d
               JOIN paper_form AS f
               ON d.dept_id = f.for_dept
               JOIN paper_file AS pf
               ON f.form_id = pf.form_id
               WHERE f.id='$id'";

      $result = $this->_server->query($query)->row_array();

      return $result;
   }

   public function addNewForm($form_data)
   {
      // var_dump($form_data);
      // die;
      // $this->db->insert('paper_file', $file_data);
      $this->db->insert('paper_form', $form_data);

      return $this->_server->affected_rows();
   }

   public function batalkanPengajuan($batalID)
   {
      var_dump($this->_server->where('form_id', $batalID)->delete('paper_form'));
      die;
      $this->_server->where('form_id', $batalID)->delete('paper_file');
   }
}
