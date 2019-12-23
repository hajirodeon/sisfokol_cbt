<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/siswa.php");
$tpl = LoadTpl("../template/siswa.html");


nocache;

//nilai
$filenya = "index.php";
$judul = "SISWA";
$judulku = "$judul  [$siswa_session]";




$jml_detik = "15000";





//ketahui detail siswa
$qyuk = mysql_query("SELECT * FROM siswa ".
						"WHERE kd = '$kd61_session'");
$ryuk = mysql_fetch_assoc($qyuk);
mysql_free_result($qyuk);
$yuk_nis = balikin($ryuk['nis']);
$yuk_nisn = balikin($ryuk['nisn']);
$yuk_nama = balikin($ryuk['nama']);
$yuk_kelas = balikin($ryuk['kelas']);



//deteksi kelas
$ikelas = explode(" ", $yuk_kelas);
$kelasa = trim($ikelas[0]);
$kelasb = trim($ikelas[1]);
$tingkat1 = $kelasa;
$tingkat2 = "$kelasa $kelasb";






//isi *START
ob_start();


?>

              
                  <!-- Info boxes -->
      <div class="row">

        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">NIS</span>
              <span class="info-box-number"><?php echo $yuk_nis;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->


        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">NISN</span>
              <span class="info-box-number"><?php echo $yuk_nisn;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
                <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">NAMA</span>
              <span class="info-box-number"><?php echo $yuk_nama;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->


                <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-user"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">KELAS</span>
              <span class="info-box-number"><?php echo $yuk_kelas;?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->








        <!-- /.col -->
      </div>
      <!-- /.row -->








                  <!-- Info boxes -->
      <div class="row">

        <!-- /.col -->
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-edit"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">SOAL SIAP KERJAKAN</span>
              <span class="info-box-number">
              	<?php
              	//detail soal
				$qku = mysql_query("SELECT * FROM m_jadwal ".
										"WHERE proses = 'true' ".
										"AND (tingkat = '$tingkat1' ".
										"OR tingkat = '$tingkat2') ".
										"ORDER BY postdate DESC");
				$rku = mysql_fetch_assoc($qku);
				$tku = mysql_num_rows($qku);
				
              	//jika ada
              	if (!empty($tku))
              		{
              		do
              			{
	              		//nilai
						$u_jkd = nosql($rku['kd']);
						$u_waktu = balikin($rku['waktu']);
						$u_pukul = balikin($rku['pukul']);
						$u_durasi = balikin($rku['durasi']);
						$u_mapel = balikin($rku['mapel']);
						$u_tingkat = balikin($rku['tingkat']);
						$u_soal_jml = balikin($rku['soal_jml']);
						$u_postdate_mulai = balikin($rku['postdate_mulai']);
						$u_postdate_selesai = balikin($rku['postdate_selesai']);
		              	
	
		              	echo '<br>
		              	<p>
						<b>'.$u_waktu.'</b>. 
						<br>
						<b>'.$u_pukul.'</b>. 
						<br>
						<b>'.$u_durasi.' Menit</b>.
						</p>
						
						<p>
						<b>'.$u_mapel.'</b>
						</p>
						
						<a href="soal.php?jkd='.$u_jkd.'" class="btn btn-block btn-danger">KERJAKAN ></a>
						<hr>
						<br>';
						}
					while ($rku = mysql_fetch_assoc($qku));
					}
				
				else
					{
					echo '<h3>
					<font color="red">BELUM ADA SOAL YANG BISA DIKERJAKAN..</font>
					</h3>';
					
					}
				?>
				              	
              	
              	
              	
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->






        <!-- /.col -->
      </div>
      <!-- /.row -->





<script>setTimeout("location.href='<?php echo $filenya;?>'", <?php echo $jml_detik;?>);</script>
<?php
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");

//diskonek
mysql_free_result();
xclose($koneksi);
exit();
?>