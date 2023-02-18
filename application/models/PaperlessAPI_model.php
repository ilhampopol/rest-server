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
         "SELECT d.description,f.id,f.desc_id,f.form_id,f.keperluan,f.dana,f.date_required,pf.lampiran,pf.file_type,f.created_by
               FROM paper_desc AS d
               JOIN paper_form AS f ON d.desc_id = f.desc_id
               JOIN paper_file AS pf ON f.form_id = pf.form_id";

      $data = $this->_server->query($query)->result_array();

      return $data;
   }

   public function getFormById($id)
   {
      $query = "SELECT f.id,d.dept_desc,f.form_id,f.desc_id,f.for_dept,f.next_dept,f.keperluan,f.dana,f.date_required,
               pf.lampiran,f.created_by,f.checked_by1,f.checked_by2,f.known_by,f.approved_by,f.paid_by
               FROM paper_dept AS d
               JOIN paper_form AS f
               ON d.dept_id = f.for_dept
               JOIN paper_file AS pf
               ON f.form_id = pf.form_id
               WHERE f.id='$id'";

      $result = $this->_server->query($query)->row_array();

      return $result;
   }

   public function addNewForm($form_data, $file_data)
   {
      $this->_server->insert('paper_form', $form_data);
      $this->_server->insert('paper_file', $file_data);

      return $this->_server->affected_rows();
   }

   public function cancelForm($batalID)
   {
      $this->_server->where('form_id', $batalID)->delete('paper_form');
      $this->_server->where('form_id', $batalID)->delete('paper_file');

      return $this->_server->affected_rows();
   }

   public function updateFile($form_id, $file_data)
   {
      $this->_server->where('form_id', $form_id);
      $this->_server->update('paper_file', $file_data);

      return $this->_server->affected_rows();
   }

   public function approveForm($form_id, $desc_id, $next_dept, $checked, $status)
   {
      $this->_server->set('desc_id', $desc_id);
      $this->_server->set('next_dept', $next_dept);
      $this->_server->set('desc_id', $desc_id);

      if ($status == 'checked-by1') {
         $this->_server->set('checked_by1', $checked);
      } elseif ($status == 'checked-by2') {
         $this->_server->set('checked_by2', $checked);
      } elseif ($status == 'known-by') {
         $this->_server->set('known_by', $checked);
      } else if ($status == 'approved-by') {
         $this->_server->set('approved_by', $checked);
      } else if ($status == 'paid-by') {
         $this->_server->set('paid_by', $checked);
      }

      $this->_server->where('form_id', $form_id);
      $this->_server->update('paper_form');

      return $this->_server->affected_rows();
   }

   public function rejectForm($form_id, $desc_id)
   {
      $this->_server->set('desc_id', $desc_id);
      $this->_server->where('form_id', $form_id);
      $this->_server->update('paper_form');

      return $this->_server->affected_rows();
   }
}
