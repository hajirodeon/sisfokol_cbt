<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");

nocache;

//nilai
$filenya = "$sumber/android/i_akun_history.php";
$filenyax = "$sumber/android/i_akun_history.php";
$judul = "History";
$juduli = $judul;



//nilai session
$sesiku = $_SESSION['sesiku'];
$sesinama = $_SESSION['sesinama'];





//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika form
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'form'))
	{
	$tablenya = "siswaujian$sesiku";
	$tablenilai = "siswanilai$sesiku";

	//daftar soal...
	$qku = mysql_query("SELECT * FROM $tablenilai ".
							"ORDER BY postdate DESC");
	$rku = mysql_fetch_assoc($qku);
	$tku = mysql_num_rows($qku);
	
	//jika ada
	if (!empty($tku))
		{
		do
			{
	  		//nilai
			$u_kd = nosql($rku['kd']);
			$u_jkd = nosql($rku['jadwal_kd']);
			$y_mulai = balikin($rku['waktu_mulai']);
			$y_selesai = balikin($rku['waktu_selesai']);
			$y_benar = balikin($rku['jml_benar']);
			$y_salah = balikin($rku['jml_salah']);
			$y_dikerjakan = balikin($rku['jml_soal_dikerjakan']);
			


			//detail e
			$qku2 = mysql_query("SELECT * FROM m_jadwal ".
									"WHERE kd = '$u_jkd'");
			$rku2 = mysql_fetch_assoc($qku2);
			$u_waktu = balikin($rku2['waktu']);
			$u_pukul = balikin($rku2['pukul']);
			$u_durasi = balikin($rku2['durasi']);
			$u_mapel = balikin($rku2['mapel']);
			$u_tingkat = balikin($rku2['tingkat']);
			$u_proses = balikin($rku2['proses']);
			$u_soal_jml = balikin($rku2['soal_jml']);
			$u_postdate_mulai = balikin($rku2['postdate_mulai']);
			$u_postdate_selesai = balikin($rku2['postdate_selesai']);

			
						

			//jika lebih dari yg ada
			if ($y_dikerjakan > $u_soal_jml)
				{
				//pastikan
				$y_dikerjakan = $u_soal_jml;
				
				
				//update
				mysql_query("UPDATE $tablenilai SET jml_soal_dikerjakan = '$y_dikerjakan' ".
								"WHERE kd = '$u_kd'");
				}
			
			
			
				      	
			echo '<table width="100%" border="0" cellpadding="3" cellspacing="3">
		    	<tr align="left">
		    	
		    		<td width="10">
		    		&nbsp;
		    		</td>
		    		
					<td>
					<p>
					<b>'.$u_waktu.'</b>. 
					<br>
					<b>'.$u_pukul.'</b>. <b>'.$u_durasi.' Menit</b>.
					</p>
					
					<p>
					<b>'.$u_mapel.'</b> 
					[<b>'.$u_soal_jml.' Soal</b>].
					</p>
					
					<font color="green">
					<p>
					<b>'.$y_dikerjakan.'</b> Soal Dikerjakan
					</p>
					
					<p>
					Mulai : <b>'.$y_mulai.'</b>
					</p>
					
					<p>
					Sampai : <b>'.$y_selesai.'</b>
					</p>	
					
					<p>
					Benar : <b>'.$y_benar.'</b>, Salah : <b>'.$y_salah.'</b>
					</p>
		    		<hr>
					</td>
	
		    	</tr>
		    </table>
		    <hr>';
		
			}
		while ($rku = mysql_fetch_assoc($qku));
		}


	//null-kan
	mysql_free_result();
	xclose($koneksi);
	exit();
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>