<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");



nocache;


//nilai
$filenya = "$sumber/android/i_main.php";


//nilai session
$sesiku = $_SESSION['sesiku'];
$brgkd = $_SESSION['brgkd'];
$sesinama = $_SESSION['sesinama'];
$kd6_session = nosql($_SESSION['sesiku']);
$notaku = nosql($_SESSION['notaku']);
$notakux = md5($notaku);





?>


  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $sumber;?>/template/adminlte/dist/css/skins/skins-biasawae.css">







<br>

<div class="row">

	<div class="col-md-12" align="center">

			<img src="img/logo.png" height="100" />
			
	</div>
	
</div>

<div class="row">

	<div class="col-md-12" align="center">

		
		<h3>
			UJIAN SISWA
		</h3>

	</div>

</div>

<hr>


<?php
if (!empty($sesiku))
	{
	//detail
	$qku = mysql_query("SELECT * FROM siswa ".
							"WHERE kd = '$sesiku'");
	$rku = mysql_fetch_assoc($qku);
	$ku_nis = balikin($rku['nis']);
	$ku_nama = balikin($rku['nama']);
	$ku_kelas = balikin($rku['kelas']);
	?>		


  <div class="row">

	<div class="col-1">

	</div>

		<div class="col-10" align="center">
			<h3>SOAL YANG SIAP DIKERJAKAN</h3>
			<hr>
			<?php
			//ketahui detail siswa
			$qyuk = mysql_query("SELECT * FROM siswa ".
									"WHERE kd = '$sesiku'");
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
			

			
			
			
			//daftar soal...
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
		              	
						echo '<table width="100%" border="0" cellpadding="3" cellspacing="3">
					    	<tr align="left">
					    	
					    		<td width="10">
					    		&nbsp;
					    		</td>
					    		
								<td>
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
								
						
								<b>
					    		<a href="#'.$u_jkd.'" onclick="$(\'#iredirect\').load(\''.$sumber.'/android/i_redirect.php?sesikode=baca&jkd='.$u_jkd.'\');" class="btn btn-block btn-danger">KERJAKAN</a>
								</b>
					    		<br>
								</td>
		
					    	</tr>
					    </table>
					    <hr>';
					
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
				
		</div>

		
		<div class="col-1">
	
		</div>
		
		
		
		
	</div>





	<?php
	//kasi log login ///////////////////////////////////////////////////////////////////////////////////
	$todayx = $today;
	
				
	//detail
	$qku = mysql_query("SELECT * FROM siswa ".
							"WHERE kd = '$sesiku'");
	$rku = mysql_fetch_assoc($qku);
	$ku_nis = cegah($rku['nis']);
	$ku_nama = cegah($rku['nama']);
	
				
	//insert
	mysql_query("INSERT INTO siswa_login(kd, siswa_kd, siswa_nis, siswa_nama, postdate) VALUES ".
					"('$x', '$sesiku', '$ku_nis', '$ku_nama', '$todayx')");
	//kasi log login ///////////////////////////////////////////////////////////////////////////////////
	
	
	
	//null-kan
	mysql_free_result();
	xclose($koneksi);
	exit();
	}
	
	
	
//null-kan
mysql_free_result();
xclose($koneksi);
exit();
?>
