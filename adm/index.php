<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/adm.php");
$tpl = LoadTpl("../template/admin.html");


nocache;

//nilai
$filenya = "index.php";
$judul = "Admin Web";
$judulku = "$judul  [$adm_session]";







//jml siswa
$qyuk = mysql_query("SELECT * FROM siswa");
$jml_siswa = mysql_num_rows($qyuk);



//jml siswa
$qyuk = mysql_query("SELECT * FROM siswa ".
						"WHERE aktif = 'true'");
$jml_siswa_sudah = mysql_num_rows($qyuk);


//jml siswa
$qyuk = mysql_query("SELECT * FROM siswa ".
						"WHERE aktif = 'false'");
$jml_siswa_belum = mysql_num_rows($qyuk);



//jml jadwal
$qyuk = mysql_query("SELECT * FROM m_jadwal");
$jml_jadwal = mysql_num_rows($qyuk);


//jml soal
$qyuk = mysql_query("SELECT * FROM m_soal");
$jml_soal = mysql_num_rows($qyuk);










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
            <span class="info-box-icon bg-orange"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SISWA</span>
              <span class="info-box-number"><?php echo $jml_siswa;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->




        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SUDAH VERIFIKASI</span>
              <span class="info-box-number"><?php echo $jml_siswa_sudah;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">BELUM VERIFIKASI</span>
              <span class="info-box-number"><?php echo $jml_siswa_belum;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="glyphicon glyphicon-list-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">JADWAL</span>
              <span class="info-box-number"><?php echo $jml_jadwal;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->





        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-edit"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SOAL</span>
              <span class="info-box-number"><?php echo $jml_soal;?></span>
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