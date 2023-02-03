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

   public function paperless_get()
   {
      $data = $this->paperless->getForm();

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

   public function image_get()
   {
      $data = $this->paperless->getImage();

      $image_data = base64_encode($data['PIC']);

      if ($image_data) {
         $this->response([
            'status' => TRUE,
            'data' => $image_data
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }
}
