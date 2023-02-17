<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/REST_Controller.php';

class PaperlessAPI extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('PaperlessAPI_model', 'paperless');
   }

   public function department_get()
   {
      $data = $this->paperless->getDepartment();

      if ($data) {
         $this->response([
            'status' => TRUE,
            'data' => $data
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }

   public function form_get()
   {
      $sort = $this->get('sort');
      $data = $this->paperless->getForm($sort);

      if ($data) {
         $this->response([
            'status' => TRUE,
            'data' => $data
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'data' => $data
         ], REST_Controller::HTTP_OK);
      }
   }

   public function allForm_get()
   {
      $data = $this->paperless->getAllForm();

      if ($data) {
         $this->response([
            'status' => TRUE,
            'data' => $data
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }

   public function formById_get()
   {
      $id = $this->get('id');
      $data = $this->paperless->getFormById($id);

      if ($data) {
         $this->response([
            'status' => TRUE,
            'data' => $data
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }
   
   public function newForm_post()
   {
      $form_data = $this->post('form_data');
      $file_data = $this->post('file_data');

      if ($this->paperless->addNewForm($form_data, $file_data) > 0) {
         $this->response([
            'status' => TRUE,
            'message' => 'Form has been created!'
         ], REST_Controller::HTTP_CREATED);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Failed to create form.'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }

   public function cancelForm_delete()
   {
      $batalID = $this->delete('batalID');

      if ($batalID === null) {
         $this->response([
            'status' => FALSE,
            'message' => 'Provide an id!'
         ], REST_Controller::HTTP_BAD_REQUEST);
      } else {
         if ($this->paperless->cancelForm($batalID) > 0) {
            // OK
            $this->response([
               'status' => TRUE,
               'id' => $batalID,
               'message' => 'form deleted.'
            ], REST_Controller::HTTP_NO_CONTENT);
         } else {
            // form_id not found
            $this->response([
               'status' => FALSE,
               'message' => 'id not found'
            ], REST_Controller::HTTP_BAD_REQUEST);
         }
      }
   }

   public function updateFile_put()
   {
      $form_id = $this->put('form_id');
      $file_data = $this->put('file_data');

      if ($this->paperless->updateFile($form_id, $file_data) > 0) {
         $this->response([
            'status' => TRUE,
            'message' => 'File has been updated'
         ], REST_Controller::HTTP_NO_CONTENT);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Failed to update file.'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }

   public function approveForm_put()
   {
      $form_id = $this->put('form_id');
      $desc_id = $this->put('desc_id');
      $next_dept = $this->put('next_dept');
      $checked = $this->put('checked');
      $status = $this->put('status');

      $data = $this->paperless->approveForm($form_id, $desc_id, $next_dept, $checked, $status);

      if ($data) {
         $this->response([
            'status' => TRUE,
            'message' => 'Form has been approved'
         ], REST_Controller::HTTP_NO_CONTENT);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Failed to approve form.'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }

   public function rejectForm_put()
   {
      $form_id = $this->put('form_id');
      $desc_id = $this->put('desc_id');

      if ($this->paperless->rejectForm($form_id, $desc_id) > 0) {
         $this->response([
            'status' => TRUE,
            'message' => 'Form has been rejected'
         ], REST_Controller::HTTP_NO_CONTENT);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Failed to reject form.'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }
}
