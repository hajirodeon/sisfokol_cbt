<?php
session_start();

require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/cek/siswa.php");
require("../inc/class/paging.php");
$tpl = LoadTpl("../template/siswa.html");

nocache;

//nilai
$filenya = "soal.php";
$judul = "[SOAL YANG DIKERJAKAN]...";
$judulku = "$judul";
$judulx = $judul;
$jkd = nosql($_REQUEST['jkd']);
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}


$sesiku = $kd61_session;


//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///ke index
if ($_POST['btnDF'])
	{
	//re-direct
	$ke = "index.php";
	xloc($ke);
	exit();
	}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



//isi *START
ob_start();


//require
require("../template/js/jumpmenu.js");
require("../template/js/swap.js");
?>



  
  <script>
  	$(document).ready(function() {
    $('#table-responsive').dataTable( {
        "scrollX": true
    } );
} );
  </script>
  
<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo '<form action="'.$filenya.'" method="post" name="formxx"><p>
<input name="jkd" type="hidden" value="'.$jkd.'">
<input name="btnDF" type="submit" value="KEMBALI KE BERANDA >" class="btn btn-danger">
</p>
<br>

</form>';






$tablenya = "siswaujian$sesiku";
$tablenya2 = "siswa_soal";
$tablenilai = "siswanilai$sesiku";

$limit = 50;



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//bikin table khusus siswaujian_siswa_kd /////////////////////////////////////////////////
mysql_query("CREATE TABLE IF NOT EXISTS $tablenya (
			  `kd` varchar(50) NOT NULL,
			  `jadwal_kd` varchar(50) NOT NULL,
			  `soal_kd` varchar(50) NOT NULL,
			  `jawab` varchar(1) NOT NULL,
			  `postdate` datetime NOT NULL,
			  `kunci` varchar(1) NOT NULL,
			  `benar` enum('true','false') NOT NULL DEFAULT 'false'
			) ENGINE=MyISAM;");
			
			
mysql_query("ALTER TABLE $tablenya ADD PRIMARY KEY (`kd`);");




//bikin table khusus siswanilai_siswa_kd /////////////////////////////////////////////////
mysql_query("CREATE TABLE IF NOT EXISTS $tablenilai (
			  `kd` varchar(50) NOT NULL,
			  `jadwal_kd` varchar(50) NOT NULL,
			  `jml_benar` varchar(3) NOT NULL,
			  `jml_salah` varchar(3) NOT NULL,
			  `waktu_mulai` datetime NOT NULL,
			  `waktu_proses` datetime NOT NULL,
			  `waktu_akhir` datetime NOT NULL,
			  `skor` varchar(5) NOT NULL,
			  `postdate` datetime NOT NULL,
			  `waktu_selesai` datetime NOT NULL,
			  `jml_soal_dikerjakan` varchar(10) NOT NULL
			) ENGINE=MyISAM;");

			
mysql_query("ALTER TABLE $tablenilai ADD PRIMARY KEY (`kd`);");
//bikin table khusus siswanilai_siswa_kd /////////////////////////////////////////////////



//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>


  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../template/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../template/adminlte/bower_components/font-awesome/css/font-awesome.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="../template/adminlte/dist/css/AdminLTE.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../template/adminlte/dist/css/skins/skins-biasawae.css">
	
	
	
	


  
	  <script>
  	$(document).ready(function() {
    $('#table-responsive').dataTable( {
        "scrollX": true
    } );
} );
  </script>
  
<?php
//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//detail jkd jadwal
$qku = mysql_query("SELECT * FROM m_jadwal ".
						"WHERE kd = '$jkd'");
$rku = mysql_fetch_assoc($qku);
mysql_free_result($qku);
$u_waktu = balikin($rku['waktu']);
$u_pukul = balikin($rku['pukul']);
$u_durasi = balikin($rku['durasi']);
$u_mapel = balikin($rku['mapel']);
$u_tingkat = balikin($rku['tingkat']);
$u_proses = balikin($rku['proses']);



//jumlah soal
$qjml = mysql_query("SELECT * FROM m_soal ".
						"WHERE jadwal_kd = '$jkd' ".
						"ORDER BY round(no) ASC");
$tjml = mysql_num_rows($qjml);	
	
	
	

//yg dikerjakan...
$qyuk = mysql_query("SELECT * FROM $tablenya ".
						"WHERE jadwal_kd = '$jkd' ".
						"AND jawab <> ''");
$ryuk = mysql_fetch_assoc($qyuk);
$yuk_dikerjakan = mysql_num_rows($qyuk);


//jika lebih, itu tjml
if ($yuk_dikerjakan > $tjml)
	{
	$yuk_dikerjakan = $tjml;
	}

?>


<script language='javascript'>
//membuat document jquery
$(document).ready(function(){

		$.ajax({
			url: "<?php echo $sumber;?>/ujian/i_timer.php?aksi=sisawaktu&jkd=<?php echo $jkd;?>&skd=<?php echo $sesiku;?>",
			type:$(this).attr("method"),
			data:$(this).serialize(),
			success:function(data){					
				$("#sisawaktu").html(data);
				}
			});
			
			






		$.ajax({
			url: "<?php echo $sumber;?>/ujian/i_timer.php?aksi=setpostdate&jkd=<?php echo $jkd;?>&skd=<?php echo $sesiku;?>",
			type:$(this).attr("method"),
			data:$(this).serialize(),
			success:function(data){					
				$("#setpostdate").html(data);
				}
			});
			
			





		
		setInterval(poll,1000);
		
		function poll()
			{
			$.ajax({
				url: "<?php echo $sumber;?>/ujian/i_jawabku.php?aksi=form&jkd=<?php echo $jkd;?>&skd=<?php echo $sesiku;?>",
				type:$(this).attr("method"),
				data:$(this).serialize(),
				success:function(data){					
					$("#jawabanku").html(data);
					}
				});
			}
		


		
});

</script>


      <div class="row">

        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="glyphicon glyphicon-edit"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?php echo $u_mapel;?> [<?php echo $tjml;?> Soal]</span>
              <span class="info-box-number">
              
				<?php
				echo '<p>
				[<b>'.$u_waktu.'</b>]. [<b>'.$u_pukul.'</b>]. [<b>'.$u_durasi.' Menit</b>].
				</p>';
				?>

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->



        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="glyphicon glyphicon-education"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Telah Dikerjakan</span>
              <span class="info-box-number">
              <div id="udahjawab">
              	<b><?php echo $yuk_dikerjakan;?></b>
				</div>

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->




        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-time"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Sisa Waktu</span>
              <span class="info-box-number">
              <div id="sisawaktu"></div>
              <div id="setpostdate"></div>

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        




       </div>
      <!-- /.row -->


              
				
<?php

mysql_free_result();

echo '</form>
<hr>';




	
//jml soal yg ada
$qyuk7 = mysql_query("SELECT * FROM m_soal ".
						"WHERE jadwal_kd = '$jkd'");
$ryuk7 = mysql_fetch_assoc($qyuk7);
$tyuk7 = mysql_num_rows($qyuk7);




//yg dijawab
$qyuk8 = mysql_query("SELECT * FROM $tablenya ".
						"WHERE jadwal_kd = '$jkd' ".
						"AND jawab <> ''");
$ryuk8 = mysql_fetch_assoc($qyuk8);
$tyuk8 = mysql_num_rows($qyuk8);






mysql_free_result();


//yg dijawab
$xyzz = md5("$jkd$sesiku");

//insert
mysql_query("INSERT INTO $tablenilai(kd, jadwal_kd, waktu_mulai, postdate) VALUES ".
				"('$xyzz', '$jkd', '$today', '$today')");

//insert
mysql_query("INSERT INTO siswa_soal_nilai(kd, jadwal_kd, waktu_mulai, postdate) VALUES ".
				"('$xyzz', '$jkd', '$today', '$today')");

					



//jika udah semua... ///////////////////////////////////////////////////////////////////////////////////
if ($tyuk7 <= $tyuk8)
	{
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlcount = "SELECT * FROM m_soal ".
					"WHERE jadwal_kd = '$jkd' ".
					"ORDER BY round(no) ASC";
	
	$sqlresult = $sqlcount;
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);
	
	
	

	do 
		{
		$i_kd = nosql($data['kd']);
		$i_nox = balikin($data['no']);
		$i_isi = balikin($data['isi']);
		$i_kunci = balikin($data['kunci']);
		$i_postdate = balikin($data['postdate']);

		
		//yg dijawab
		$qyuk = mysql_query("SELECT * FROM $tablenya ".
								"WHERE jadwal_kd = '$jkd' ".
								"AND soal_kd = '$i_kd' ".
								"AND jawab <> ''");
		$ryuk = mysql_fetch_assoc($qyuk);
		$yuk_kd = nosql($ryuk['kd']);
		$yuk_jawabku = balikin($ryuk['jawab']);
		
		
		//jika benar, true
		if ($i_kunci == $yuk_jawabku)
			{
			$setjawab = "true";	
			}
					
		else if ($i_kunci <> $yuk_jawabku)
			{
			$setjawab = "false";	
			}
			


		//yg dijawab
		$qyuk3 = mysql_query("SELECT * FROM $tablenilai ".
								"WHERE jadwal_kd = '$jkd'");
		$ryuk3 = mysql_fetch_assoc($qyuk3);
		$tyuk3 = mysql_num_rows($qyuk3);
						
		
		//jika ada, gak usah update...
		if (!empty($tyuk3))
			{
			//update
			mysql_query("UPDATE $tablenya SET kunci = '$i_kunci', ".
							"benar = '$setjawab' ".
							"WHERE kd = '$yuk_kd'");
							
			
			//update
			mysql_query("UPDATE $tablenya2 SET kunci = '$i_kunci', ".
							"benar = '$setjawab' ".
							"WHERE kd = '$yuk_kd'");
							
							
			//update
			mysql_query("UPDATE siswa_soal_nilai SET kunci = '$i_kunci', ".
							"benar = '$setjawab' ".
							"WHERE kd = '$yuk_kd'");
			}
		}
	while ($data = mysql_fetch_assoc($result));

	
	
	
	mysql_free_result();
	
	
	//hitung yg benar
	$qyuk2 = mysql_query("SELECT * FROM $tablenya ".
							"WHERE jadwal_kd = '$jkd' ".
							"AND benar = 'true'");
	$ryuk2 = mysql_fetch_assoc($qyuk2);
	$jml_benar = mysql_num_rows($qyuk2);
	$jml_salah = $count - $jml_benar; 


	//update
	mysql_query("UPDATE $tablenilai SET jml_benar = '$jml_benar', ".
					"jml_salah = '$jml_salah', ".
					"postdate = '$today' ".
					"WHERE jadwal_kd = '$jkd'");
					
					
	//update
	mysql_query("UPDATE siswa_soal_nilai SET jml_benar = '$jml_benar', ".
					"jml_salah = '$jml_salah', ".
					"postdate = '$today' ".
					"WHERE jadwal_kd = '$jkd'");
	?>



        <!-- /.col -->
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-duplicate"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Rekap Jawaban</span>
              <span class="info-box-number">
              [Benar : <font color="green"><?php echo $jml_benar;?></font>].
              [Salah : <font color="red"><?php echo $jml_salah;?></font>]. 

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        
	<?php
	}



else		

	{
	
	?>




	<style>
	
	
	#myfooter{
	   position: fixed;
	   left: 0;
	   bottom: 0;
	  height: 6em;
	  background-color: #f5f5f5;
	  text-align: center;
	   width: 100%;
	   color: green;;
	
	}
	
	
	
	
	</style>
	




	   <!-- /.col -->
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-pushpin"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">PERHATIAN</span>
              <span class="info-box-number">
              Pastikan semua soal telah dikerjakan, selanjutnya bisa tekan tombol "Selesai Mengerjakan". Terima Kasih.  

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        



	<div id="myfooter">

	   <!-- /.col -->
        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="info-box">
            <div class="info-box-content">
              <span class="info-box-text">DIJAWAB</span>
              <span class="info-box-number">
              
              <div id="jawabanku"></div>  

              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
       	 
	</div>

	<?php
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlcount = "SELECT * FROM m_soal ".
					"WHERE jadwal_kd = '$jkd' ".
					"ORDER BY round(no) ASC";
	
	$sqlresult = $sqlcount;
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);
	
	
	echo "&nbsp;";
	
	do 
		{
		if ($warna_set ==0)
			{
			$warna = $warna01;
			$warna_set = 1;
			}
		else
			{
			$warna = $warna02;
			$warna_set = 0;
			}

		$nomer = $nomer + 1;
		$i_kd = nosql($data['kd']);
		$i_no = balikin($data['no']);
		$i_kunci = balikin($data['kunci']);
		$i_isi = balikin($data['isi']);
		$i_postdate = balikin($data['postdate']);

		
		//yg dijawab
		$qyuk = mysql_query("SELECT * FROM $tablenya ".
								"WHERE jadwal_kd = '$jkd' ".
								"AND soal_kd = '$i_kd'");
		$ryuk = mysql_fetch_assoc($qyuk);
		mysql_free_result($qyuk);
		$yuk_kdku = nosql($ryuk['kd']);
		$yuk_jawabku = balikin($ryuk['jawab']);
		
		
		
		
		//nilai
		$xyz = md5("$sesiku$jkd$i_kd");
		

		//insert
		mysql_query("INSERT INTO $tablenya(kd, jadwal_kd, soal_kd, jawab, postdate) VALUES ".
						"('$xyz', '$jkd', '$i_kd', '', '$today')");
						


		//insert
		mysql_query("INSERT INTO $tablenya2(kd, jadwal_kd, siswa_kd, soal_kd, jawab, postdate) VALUES ".
						"('$xyz', '$jkd', '$sesiku', '$i_kd', '', '$today')");

								
		?>
			<script language='javascript'>
		//membuat document jquery
		$(document).ready(function(){
						
			$('#xpilih<?php echo $nomer;?>').change(function() {
				var nilku = $(this).val();


				$('#iproses<?php echo $i_kd;?>').show();
				
				$.ajax({
					url: "<?php echo $sumber;?>/ujian/i_jawab.php?aksi=simpan&jkd=<?php echo $jkd;?>&skd=<?php echo $sesiku;?>&soalkd=<?php echo $i_kd;?>&nilku="+nilku,
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){				
						$("#ihasil<?php echo $nomer;?>").html(data);
						$('#iproses<?php echo $i_kd;?>').hide();
						}
					});
				
				
				
				
				$.ajax({
					url: "<?php echo $sumber;?>/ujian/i_jawab.php?aksi=hitung&jkd=<?php echo $jkd;?>&skd=<?php echo $sesiku;?>&soalkd=<?php echo $i_kd;?>&nilku="+nilku,
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){					
						$("#udahjawab").html(data);
						}
					});
				

				
				$.ajax({
					url: "<?php echo $sumber;?>/ujian/i_timer.php?aksi=setpostdate&jkd=<?php echo $jkd;?>&skd=<?php echo $sesiku;?>",
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){					
						$("#setpostdate").html(data);
						}
					});
					
				
		    });


				
		});
		
		</script>




		<?php

		echo '<a name="ku'.$i_kd.'"></a>
		
		<div class="table-responsive">          
		<table class="table" border="1">
		<thead>
		<tr valign="top" bgcolor="'.$warnaheader.'">
		<td width="50"><strong><font color="'.$warnatext.'">NO</font></strong></td>
		<td><strong><font color="'.$warnatext.'">SOAL</font></strong></td>
		</tr>
		</thead>
		<tbody>';
				
		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td align="center">'.$i_no.'.</td>
		<td>
		'.$i_isi.'
		
		<hr>
		
		<p>
		 
		<form name="xformx'.$nomer.'" id="xformx'.$nomer.'">
		Jawab : <select name="xpilih'.$nomer.'" id="xpilih'.$nomer.'" class="btn btn-warning">
					<option value="'.$yuk_jawabku.'" selected>'.$yuk_jawabku.'</option>	
					<option value="A">A</option>	
					<option value="B">B</option>	
					<option value="C">C</option>	
					<option value="D">D</option>	
					<option value="E">E</option>	
					</select>			
		
		</p>		
		</form>
				
		<div id="iproses'.$i_kd.'" style="display:none">
			<img src="'.$sumber.'/template/img/progress-bar.gif" width="100" height="16">
		</div>
		
		<div id="ihasil'.$nomer.'"></div>
		
		
		</td>
        </tr>
		</tbody>
	  	</table>
	  	</div>';
	
		
		//update ///////////////////////////////////////////////////////////////////////////////////
		//yg dijawab
		$qyuk = mysql_query("SELECT * FROM $tablenya ".
								"WHERE jadwal_kd = '$jkd' ".
								"AND soal_kd = '$i_kd' ".
								"AND jawab <> ''");
		$ryuk = mysql_fetch_assoc($qyuk);
		$yuk_kd = nosql($ryuk['kd']);
		$yuk_jawabku = balikin($ryuk['jawab']);
		
		
		//jika benar, true
		if ($i_kunci == $yuk_jawabku)
			{
			$setjawab = "true";	
			}
					
		else if ($i_kunci <> $yuk_jawabku)
			{
			$setjawab = "false";	
			}
			

		//update
		mysql_query("UPDATE $tablenya2 SET kunci = '$i_kunci', ".
						"benar = '$setjawab' ".
						"WHERE kd = '$yuk_kdku'");
						
						

		//update
		mysql_query("UPDATE $tablenya SET kunci = '$i_kunci', ".
						"benar = '$setjawab' ".
						"WHERE kd = '$yuk_kdku'");
		}
	while ($data = mysql_fetch_assoc($result));
	



				
	?>
	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
		
		$("#btnSELESAI").on('click', function(){
			
			$("#xformselesai").submit(function(){
				$.ajax({
					url: "<?php echo $sumber;?>/ujian/i_jawab.php?aksi=selesai&jkd=<?php echo $jkd;?>&skd=<?php echo $sesiku;?>",
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){					
						$("#iprosesku").show();
						$("#ihasilselesai").html(data);
						}
					});
				return false;
			});
		
		
		});	


			
	});
	
	</script>


	<?php
		
	
	echo '<br>
	<div id="ihasilselesai"></div>
	<div id="iprosesku" style="display:none">
		<img src="'.$sumber.'/template/img/progress-bar.gif" width="100" height="16">
	</div>

	<form name="xformselesai" id="xformselesai">
	<hr>
	<input name="btnSELESAI" id="btnSELESAI" type="submit" class="btn btn-block btn-danger" value="SELESAI MENGERJAKAN.">
	<hr>
	
	</form>
	
	
	<br>
	<br>
	<br>';
	
	
	
	
	
	
	mysql_free_result();
	
	
	//jml soal yg ada
	$qyuk7 = mysql_query("SELECT * FROM m_soal ".
							"WHERE jadwal_kd = '$jkd'");
	$ryuk7 = mysql_fetch_assoc($qyuk7);
	$tyuk7 = mysql_num_rows($qyuk7);
	mysql_free_result($qyuk7);
	
	//hitung yg benar
	$qyuk2 = mysql_query("SELECT * FROM $tablenya ".
							"WHERE jadwal_kd = '$jkd' ".
							"AND benar = 'true'");
	$ryuk2 = mysql_fetch_assoc($qyuk2);
	mysql_free_result($qyuk2);
	$jml_benar = mysql_num_rows($qyuk2);
	$jml_salah = $count - $jml_benar; 
	$xyzz = md5("$jkd$sesiku");


					

	//update
	mysql_query("UPDATE $tablenilai SET jml_benar = '$jml_benar', ".
					"jml_salah = '$jml_salah', ".
					"postdate = '$today' ".
					"WHERE jadwal_kd = '$jkd'");
					
					
	//update
	mysql_query("UPDATE siswa_soal_nilai SET jml_benar = '$jml_benar', ".
					"jml_salah = '$jml_salah', ".
					"postdate = '$today' ".
					"WHERE jadwal_kd = '$jkd'");
	}	






//isi
$isi = ob_get_contents();
ob_end_clean();

require("../inc/niltpl.php");


//null-kan
mysql_free_result();
xclose($koneksi);
exit();
?>