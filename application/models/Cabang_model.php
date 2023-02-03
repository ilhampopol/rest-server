<?php 

class Cabang_model extends CI_Model {
   public function getCabang() {
   $query = "SELECT Kode,Deskripsi,RTRIM(Alamat) AS Alamat,RTRIM(Kota) AS Kota,RTRIM(Telepon) AS Telepon,RTRIM(Jenis) AS Jenis
			FROM invCABANG
         WHERE Aktif = 'Y' AND Jenis IN('RETAIL','GROSIR')
         ORDER BY Kode";

   return $this->db->query($query)->result_array();
   }
}

?>