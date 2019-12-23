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
$filenya = "rekap.php";
$judul = "REKAP";
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




$sesiku = $kd61_session;



//isi *START
ob_start();


?>

              

                  <!-- Info boxes -->
      <div class="row">

        <!-- /.col -->
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-report"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">REKAP</span>
              <span class="info-box-number">


				<?php
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