<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Cabang extends REST_Controller {
   public function __construct() {
      parent::__construct();
      $this->load->model('Cabang_model', 'cabang');
   }

   public function index_get() {

   $cabang = $this->cabang->getCabang();
   
   if($cabang) {
         $this->response([
            'status' => TRUE,
            'data' => $cabang
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data not found'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }
}

?>