<?php
class Sales_model extends CI_Model
{
   public function getSales()
   {
      $query = "SELECT SUM(Net_Transaksi) AS SALES
         FROM dtTRANSAKSI
         WHERE DATEDIFF(MONTH,Tanggal,CURRENT_TIMESTAMP)<=0 AND Void='N'
         UNION ALL
         SELECT SUM(Net_Transaksi) AS YEARLY
         FROM dtTRANSAKSI
         WHERE DATEDIFF(YEAR,Tanggal,CURRENT_TIMESTAMP)<=0 AND Void='N'";

      return $this->db->query($query)->result_array();
   }

   public function getTargetSales()
   {
      $thisYear = date('Y');
      $thisMonth = 't' . date('n');
      $query = "SELECT SUM($thisMonth) AS Total FROM invTARGETSALES WHERE Tahun=$thisYear";

      return $this->db->query($query)->result_array();
   }

   public function getDistOut()
   {
      $query = "SELECT COUNT(idnya) AS Dist FROM invDISTOUT WHERE DATEDIFF(MONTH,Tanggal,CURRENT_TIMESTAMP)<=0 AND Status='Process'";

      return $this->db->query($query)->result_array();
   }

   public function getMonthlySales()
   {
      $query = "SELECT MONTH(Tanggal) AS month,SUM(Net_Transaksi) AS nett
               FROM dtTRANSAKSI
               WHERE DATEDIFF(YEAR,Tanggal,'2023-01-01')<=0 AND Void='N'
               GROUP BY MONTH(Tanggal) ORDER BY MONTH(Tanggal)";

      return $this->db->query($query)->result_array();
   }

   public function getSalesByBranch()
   {
      $query = "SELECT LEFT(T.No_Transaksi,5) AS branch_code, C.Deskripsi AS branch_name, SUM(T.Net_Transaksi) AS nett
               FROM dtTRANSAKSI AS T
               JOIN invCABANG AS C
               ON LEFT(T.No_Transaksi,5) = C.Kode
               WHERE DATEDIFF(YEAR,T.Tanggal,CURRENT_TIMESTAMP)=0 AND T.Void='N' AND C.Aktif='Y'
               GROUP BY LEFT(T.No_Transaksi,5),C.Kode,C.Deskripsi
               ORDER BY C.Kode";

      return $this->db->query($query)->result_array();
   }

   public function getListSales($date1, $date2)
   {
      if ($date1 && $date2 == NULL) {
         $query = "SELECT COUNT(idnya) AS Total_Transaksi FROM dtTRANSAKSI";

         return $this->db->query($query)->result_array();
      } else {
         $queryBranch = "SELECT Kode,Deskripsi FROM invCABANG
                     WHERE Jenis IN ('GROSIR','RETAIL') AND Aktif='Y'
                     ORDER BY Kode";

         $branch = $this->db->query($queryBranch)->result_array();
         $data = [];
         foreach ($branch as $b) {
            $branchCode = $b['Kode'];
            $branchName = $b['Deskripsi'];
            $queryCredit = "SELECT SUM(Net_Transaksi) AS tr_credit FROM dtTRANSAKSI
                           WHERE Tanggal BETWEEN '$date1' AND '$date2' AND Void='N' AND Jenis_Bayar='KREDIT'
                           AND LEFT(No_Transaksi,5)='$branchCode'";
            $credit = $this->db->query($queryCredit)->result_array();

            $queryCash = "SELECT SUM(Net_Transaksi) AS tr_cash FROM dtTRANSAKSI
                           WHERE Tanggal BETWEEN '$date1' AND '$date2' AND Void='N' AND Jenis_Bayar='LUNAS'
                           AND LEFT(No_Transaksi,5)='$branchCode'";
            $cash = $this->db->query($queryCash)->result_array();
            $data[] = [
               'branchCode' => $branchCode,
               'branchName' => $branchName,
               'credit' => $credit,
               'cash' => $cash
            ];
         }
         return $data;
      }
   }

   public function getDetailCash($code, $date1, $date2)
   {
      $query = "SELECT RTRIM(a.No_Transaksi) AS No_Transaksi,SUM(a.Qty) AS Qty,SUM(a.Total_Nilai) AS Total_Nilai,
               b.Bayar_Cash,b.Bayar_Card,RTRIM(b.Nama_Customer) AS Nama_Customer,RTRIM(c.Jenis_Card) AS Jenis_Card
               FROM dtITEMTRANSAKSI a
               INNER JOIN dtTRANSAKSI b ON a.No_Transaksi = b.No_Transaksi
               LEFT JOIN dtITEMTRANSAKSICARD c ON a.No_Transaksi = c.No_Transaksi
               WHERE B.Tanggal BETWEEN '$date1' AND '$date2' AND b.Jenis_Bayar='LUNAS' AND b.Void='N' AND LEFT(b.No_Transaksi,5)='$code' AND a.Void='N' AND a.Qty > 0
               GROUP BY a.No_Transaksi,b.Nama_Customer,b.Bayar_Cash,b.Bayar_Card,c.Jenis_Card
               ORDER BY a.No_Transaksi";

      $test = $this->db->query($query)->result_array();
      if ($test == null) {
         $test = 'kosong';
         return $test;
      } else {
         $test = $test;
         return $test;
      }

   }

   public function getDetailCredit($code, $date1, $date2)
   {
      $query = "SELECT RTRIM(d.No_Transaksi) AS No_Transaksi,RTRIM(b.No_ID) AS No_ID,RTRIM(b.Nama) AS Nama,a.Net_Transaksi,a.Bayar_Cash,a.Bayar_Card,SUM(d.Qty) AS Qty,c.Deskripsi
               FROM dtTRANSAKSI a
               JOIN invCUSTOMER b ON a.Kode_Customer = b.No_ID
               JOIN invCabang c ON c.Kode = LEFT(a.No_Transaksi,5)
               JOIN dtITEMTRANSAKSI d ON a.No_Transaksi = d.No_Transaksi
               WHERE DATEDIFF(DAY,Tanggal,'$date1')<=0 AND DATEDIFF(DAY,Tanggal,'$date2')>=0 AND a.Jenis_Bayar = 'KREDIT'
               AND a.Void = 'N' AND LEFT(a.No_Transaksi,5) = '$code' 
               GROUP BY a.Bayar_Cash,a.Bayar_Card,b.No_ID,b.Nama,c.Deskripsi,d.No_Transaksi,a.Net_Transaksi
               ORDER BY d.No_Transaksi ASC";

      return $this->db->query($query)->result_array();
   }

   public function getDetailItem($transId)
   {
      $query = "SELECT RTRIM(No_Transaksi) AS No_Transaksi,PLU,RTRIM(Deskripsi) AS Deskripsi,Qty,Harga_Jual,Diskon,Total_Nilai
               FROM dtITEMTRANSAKSI
               WHERE No_Transaksi='$transId'";

      return $this->db->query($query)->result_array();
   }
}
