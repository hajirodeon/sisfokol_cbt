<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");

nocache;

//nilai
$filenya = "$sumber/android/i_akun_profil.php";
$filenyax = "$sumber/android/i_akun_profil.php";
$judul = "Profil Diri";
$juduli = $judul;



//nilai session
$sesiku = $_SESSION['sesiku'];
$sesinama = $_SESSION['sesinama'];





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika form
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'form'))
	{
	//detail
	$qku = mysql_query("SELECT * FROM siswa ".
							"WHERE kd = '$sesiku'");
	$rku = mysql_fetch_assoc($qku);
	$ku_nis = balikin($rku['nis']);
	$ku_nama = balikin($rku['nama']);
	$ku_kelas = balikin($rku['kelas']);
	
	echo '<div class="row">
	
	<div class="col-md-12">
	
	<table width="100%" border="0" cellpadding="5" cellspacing="5">
	<tr align="top">
	<td width="10">&nbsp;</td>
	
	<td>
		<p>
		NIS : 
		<br>
		<b>'.$ku_nis.'</b>
		
		</p>
		
		
		<p>
		Nama : 
		<br>
		<b>'.$ku_nama.'</b>
		</p>
		
		
		<p>
		Kelas : 
		<br>
		<b>'.$ku_kelas.'</b>
		</p>
				
	</td>
	
	<td width="10">&nbsp;</td>
	</tr>
	</table>


	</div>
	

	
	
	</div>';

	//null-kan
	mysql_free_result();
	xclose($koneksi);
	exit();
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>