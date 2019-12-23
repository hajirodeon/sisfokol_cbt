<?php
session_start();

require("inc/config.php");
require("inc/fungsi.php");
require("inc/koneksi.php");
	




$filenyax = "i_index.php";




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika simpan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'simpan'))
	{
	//ambil nilai
	$c_nis = trim(cegah($_GET['c_nis']));
	$c_nisn = trim(cegah($_GET['c_nisn']));
	$c_tgl_lahir = trim(cegah($_GET['c_tgl_lahir']));
	$c_tgl_lahir2 = balikin($_GET['c_tgl_lahir']);
	
	//empty
	if ((empty($c_nis)) OR (empty($c_nisn)) OR (empty($c_tgl_lahir)))
		{
		echo '<font color="red">
		<h3>INPUT TIDAK LENGKAP</h3>
		</font>';	
		} 
	else
		{
		//cek ////////////////////////////////////////////////////////////////////////////////////////////
		$qcc = mysql_query("SELECT * FROM siswa ".
								"WHERE nis = '$c_nis' ".
								"AND nisn = '$c_nisn' ".
								"AND lahir_tgl = '$c_tgl_lahir'");
		$rcc = mysql_fetch_assoc($qcc);
		$tcc = mysql_num_rows($qcc);
		$cc_aktif = balikin($rcc['aktif']);
		$cc_kd = nosql($rcc['kd']);
		$cc_nama = balikin($rcc['nama']);
		$cc_kelas = balikin($rcc['kelas']);
		$cc_lahir_tmp = balikin($rcc['lahir_tmp']);
		
		
		//jika null
		if (empty($tcc))
			{
			//re-direct
			echo '<font color="red">
			<h3>Verifikasi Tidak Cocok. Harap Diperhatikan...!!</h3>
			</font>';

			exit();
			}
		
			
		else
			{
			//pass
			$passku = substr($x,0,5);
			$passkux = md5($passku);
			
		
		
			//jika belum aktif
			if ($cc_aktif == "false")
				{	
				//bikin user
				mysql_query("UPDATE siswa SET usernamex = '$c_nis', ".
								"passwordx = '$passkux', ".
								"passwordx2 = '$passku', ".
								"aktif = 'true', ".
								"aktif_postdate = '$today' ".
								"WHERE kd = '$cc_kd'");
				}
			
			
			
			//cocok
			echo '<font color="green">
			<h3>VERIFIKASI BERHASIL</h3>
			</font>
			
			<p>
			[NIS : '.$c_nis.']. [NISN : '.$c_nisn.']. [Nama : '.$cc_nama.']. [Kelas : '.$cc_kelas.'].
			</p>
			
			
			
			<a href="siswa_prt.php?ckd='.$cc_kd.'" target="_blank" class="btn btn-danger"> UNDUH KARTU UJIAN </a>';
			}
		}		
			
	
	exit();
	}





exit();
?>