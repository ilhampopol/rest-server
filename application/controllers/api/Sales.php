<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/REST_Controller.php';

class Sales extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model('Sales_model', 'sales');

      $this->methods['index_get']['limit'] = [2];
   }

   public function index_get()
   {
      $allSales = $this->sales->getSales();
      $target = $this->sales->getTargetSales();
      $dist = $this->sales->getDistOut();
      $monthlySales = $this->sales->getMonthlySales();
      $salesByBranch = $this->sales->getSalesByBranch();

      if ($allSales || $target || $dist) {
         $this->response([
            'status' => TRUE,
            'sales' => $allSales,
            'target' => $target,
            'dist' => $dist,
            'monthlySales' => $monthlySales,
            'salesByBranch' => $salesByBranch
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data not found'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }

   public function listSales_get()
   {
      $date1 = $this->get('date1');
      $date2 = $this->get('date2');

      $sales = $this->sales->getListSales($date1, $date2);

      if ($sales) {
         $this->response([
            'status' => TRUE,
            'data' => $sales
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }

   public function detailCash_get()
   {
      $code = $this->get('code');
      $date1 = $this->get('date1');
      $date2 = $this->get('date2');

      $detail = $this->sales->getDetailCash($code, $date1, $date2);

      if ($detail) {
         $this->response([
            'status' => TRUE,
            'data' => $detail
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }

   public function detailCredit_get()
   {
      $code = $this->get('code');
      $date1 = $this->get('date1');
      $date2 = $this->get('date2');

      $detail = $this->sales->getDetailCredit($code, $date1, $date2);

      if ($detail) {
         $this->response([
            'status' => TRUE,
            'data' => $detail
         ], REST_Controller::HTTP_OK);
      } else {
         $this->response([
            'status' => FALSE,
            'message' => 'Data Not Found!'
         ], REST_Controller::HTTP_NOT_FOUND);
      }
   }

   public function detailItem_get()
   {
      $transId = $this->get('transId');

      $data = $this->sales->getDetailItem($transId);

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
}
