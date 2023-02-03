<?php
class PaperlessAPI_model extends CI_Model
{
   public function getForm()
   {
      $db2 = $this->load->database('paperless', true);

      $query = "SELECT RTRIM(F.Nomor) AS Nomor,RTRIM(F.Deskripsi) AS Deskripsi,RTRIM(S.Nama) AS Nama,RTRIM(F.Proses) AS Proses,F.Tanggal
                  FROM pepFormulir AS F
                  JOIN pepStatus AS S
                  ON F.Status = S.Status
                  ORDER BY F.Tanggal";

      return $db2->query($query)->result_array();
   }

   public function getImage()
   {
      $db2 = $this->load->database('paperless', true);

      $query = "SELECT PIC FROM pepFormulir WHERE Nomor='RUN0404202301003'";

      return $db2->query($query)->row_array();
   }
}
