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
      // $form_data = $this->input->post('form_data');
      // $file_data = $this->post('file_data');
      $request = file_get_contents('php://input');
      $data = json_decode($request, true);
      var_dump($data);
      die;

      if ($this->paperless->addNewForm($form_data) > 0) {
         $this->response([
            'status' => TRUE,
            'message' => 'New for has been created!'
         ], REST_Controller::HTTP_CREATED);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Failed to create form.'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }

   public function pengajuan_delete()
   {
      $batalID = $this->get('batalID');
      $data = $this->paperless->batalkanPengajuan($batalID);

      if ($data) {
         $this->response([
            'status' => TRUE,
            'data' => $data
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_BAD_REQUEST);
      }
   }
}
