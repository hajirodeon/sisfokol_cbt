<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/adm.php");
$tpl = LoadTpl("../template/admsoal.html");


nocache;

//nilai
$filenya = "index.php";
$judul = "Admin Soal";
$judulku = "$judul  [$adm_session]";







//jml siswa
$qyuk = mysql_query("SELECT * FROM m_soal");
$jml_siswa = mysql_num_rows($qyuk);









//isi *START
ob_start();





//isi *START
ob_start();


?>

              
                  <!-- Info boxes -->
      <div class="row">

        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SOAL</span>
              <span class="info-box-number"><?php echo $jml_siswa;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->








        <!-- /.col -->
      </div>
      <!-- /.row -->




            
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");

//diskonek
xfree($qbw);
xclose($koneksi);
exit();
?>