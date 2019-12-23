<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/admin.html");

nocache;

//nilai
$filenya = "lap_hasil_ujian.php";
$judul = "[LAPORAN] Hasil Ujian";
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


$limit = 1000;




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika ke daftar
if ($_POST['btnDF'])
	{
	//re-direct
	$ke = "lap.php";
	xloc($ke);
	exit();
	}






//nek batal
if ($_POST['btnBTL'])
	{
	//nilai
	$jkd = nosql($_POST['jkd']);

	//re-direct
	$ke = "$filenya?jkd=$jkd";
	xloc($ke);
	exit();
	}





//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$jkd = nosql($_POST['jkd']);	
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?jkd=$jkd&kunci=$kunci";
	xloc($ke);
	exit();
	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





//jika excel
if ($s == "excel")
	{
	//require
	require('../../inc/class/excel/OLEwriter.php');
	require('../../inc/class/excel/BIFFwriter.php');
	require('../../inc/class/excel/worksheet.php');
	require('../../inc/class/excel/workbook.php');


	//detail jkd jadwal
	$qku = mysql_query("SELECT * FROM m_jadwal ".
							"WHERE kd = '$jkd'");
	$rku = mysql_fetch_assoc($qku);
	$u_waktu = balikin($rku['waktu']);
	$u_pukul = balikin($rku['pukul']);
	$u_durasi = balikin($rku['durasi']);
	$u_mapel = balikin($rku['mapel']);
	$u_tingkat = balikin($rku['tingkat']);
	$u_soal_jml = balikin($rku['soal_jml']);
	



	

	//nama file e...
	$i_filename = seo_friendly_url("jawabsemua-$u_waktu-$u_mapel-$u_tingkat.xls");
	$i_judul = "JawabSemua";
	



	//header file
	function HeaderingExcel($i_filename)
		{
		header("Content-type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename=$i_filename");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
		header("Pragma: public");
		}

	
	
	
	//bikin...
	HeaderingExcel($i_filename);
	$workbook = new Workbook("-");
	$worksheet1 =& $workbook->add_worksheet($i_judul);
	$worksheet1->write_string(0,0,"NO.");
	$worksheet1->write_string(0,1,"SOAL");
	$worksheet1->write_string(0,2,"DIKERJAKAN");
	$worksheet1->write_string(0,3,"BENAR");
	$worksheet1->write_string(0,4,"SALAH");
	$worksheet1->write_string(0,5,"NILAI");

	

	//query	
	$qyukx = mysql_query("SELECT * FROM m_soal ".
							"WHERE jadwal_kd = '$jkd' ".
							"ORDER BY round(no) ASC");
	$ryukx = mysql_fetch_assoc($qyukx);

	do 
		{
		$i_kd = nosql($ryukx['kd']);
		$i_no = balikin($ryukx['no']);
		$dt_nox = $dt_nox + 1;
				 
		//detail nilai
		$qmboh = mysql_query("SELECT * FROM siswa_soal ".
								"WHERE jadwal_kd = '$jkd' ".
								"AND soal_kd = '$i_kd'");
		$rmboh = mysql_fetch_assoc($qmboh);
		$mboh_total = mysql_num_rows($qmboh);

		
		//detail nilai
		$qmboh2 = mysql_query("SELECT * FROM siswa_soal ".
								"WHERE jadwal_kd = '$jkd' ".
								"AND soal_kd = '$i_kd' ".
								"AND benar = 'true'");
		$rmboh2 = mysql_fetch_assoc($qmboh2);
		$mboh_jml_benar = mysql_num_rows($qmboh2);
		
		
		//detail nilai
		$qmboh3 = mysql_query("SELECT * FROM siswa_soal ".
								"WHERE jadwal_kd = '$jkd' ".
								"AND soal_kd = '$i_kd' ".
								"AND benar = 'false'");
		$rmboh3 = mysql_fetch_assoc($qmboh3);
		$mboh_jml_salah = mysql_num_rows($qmboh3);
		
		
		
		//total siswa
		$mboh_total = $mboh_jml_benar + $mboh_jml_salah;
		
		//nilai
		$mboh_nilai = round(($mboh_jml_benar / $mboh_total) * 100,2);
				 

		$i_nox = "NOMOR $i_no";		
			
			  
		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_nox);
		$worksheet1->write_string($dt_nox,1,$i_nox);
		$worksheet1->write_string($dt_nox,2,$mboh_total);
		$worksheet1->write_string($dt_nox,3,$mboh_jml_benar);
		$worksheet1->write_string($dt_nox,4,$mboh_jml_salah);
		$worksheet1->write_string($dt_nox,5,$mboh_nilai);
		}
	while ($ryukx = mysql_fetch_assoc($qyukx));
		
	
	//close
	$workbook->close();

	
	
	//re-direct
	//exit
	xclose($koneksi);
	$ke = "$filenya?jkd=$jkd";
	xloc($ke);
	exit();
	}



else
	{		
	//isi *START
	ob_start();
	
	
	//require
	require("../../template/js/jumpmenu.js");
	require("../../template/js/checkall.js");
	require("../../template/js/swap.js");
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
	//detail jkd jadwal
	$qku = mysql_query("SELECT * FROM m_jadwal ".
							"WHERE kd = '$jkd'");
	$rku = mysql_fetch_assoc($qku);
	$u_waktu = balikin($rku['waktu']);
	$u_pukul = balikin($rku['pukul']);
	$u_durasi = balikin($rku['durasi']);
	$u_mapel = balikin($rku['mapel']);
	$u_tingkat = balikin($rku['tingkat']);
	$u_soal_jml = balikin($rku['soal_jml']);
	$u_postdate_mulai = balikin($rku['postdate_mulai']);
	$u_postdate_selesai = balikin($rku['postdate_selesai']);
	
	
	
	
	echo '<form action="'.$filenya.'" method="post" name="formxx">
	
	<p>
	[<b>'.$u_waktu.'</b>]. [<b>'.$u_pukul.'</b>]. [<b>'.$u_durasi.' Menit</b>].
	</p>
	
	<p>
	Mapel : <b>'.$u_mapel.'</b>, Kelas : <b>'.$u_tingkat.'</b>
	</p>
	
	
	<p>
	Mulai : <b>'.$u_postdate_mulai.'</b>, Selesai : <b>'.$u_postdate_selesai.'</b>
	</p>
	
	
	
	<p>
	<input name="jkd" type="hidden" value="'.$jkd.'">
	<input name="btnDF" type="submit" value="LIHAT JADWAL LAIN >" class="btn btn-danger">
	</p>
	<br>
	
	</form>
	
	
	
	
	
	
	
	
	<form action="'.$filenya.'" method="post" name="formxx">';
	
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
	$target = "$filenya?jkd=$jkd";
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);
		
	
	echo '<hr>
	<p>
	<input name="s" type="hidden" value="'.$s.'">
	<input name="jkd" type="hidden" value="'.$jkd.'">
	
	</p>
		
		
	
	
	 
	<a href="'.$filenya.'?s=excel&jkd='.$jkd.'" class="btn btn-success">EXPORT EXCEL >></a>
		
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td><strong><font color="'.$warnatext.'">SOAL</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">DIKERJAKAN</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">BENAR</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">SALAH</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">NILAI</font></strong></td>
	</tr>
	</thead>
	<tbody>';
	
	if ($count != 0)
		{
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
			
			
			//detail nilai
			$qmboh = mysql_query("SELECT * FROM siswa_soal ".
									"WHERE jadwal_kd = '$jkd' ".
									"AND soal_kd = '$i_kd'");
			$rmboh = mysql_fetch_assoc($qmboh);
			$mboh_total = mysql_num_rows($qmboh);

			
			//detail nilai
			$qmboh2 = mysql_query("SELECT * FROM siswa_soal ".
									"WHERE jadwal_kd = '$jkd' ".
									"AND soal_kd = '$i_kd' ".
									"AND benar = 'true'");
			$rmboh2 = mysql_fetch_assoc($qmboh2);
			$mboh_jml_benar = mysql_num_rows($qmboh2);
			
			
			//detail nilai
			$qmboh3 = mysql_query("SELECT * FROM siswa_soal ".
									"WHERE jadwal_kd = '$jkd' ".
									"AND soal_kd = '$i_kd' ".
									"AND benar = 'false'");
			$rmboh3 = mysql_fetch_assoc($qmboh3);
			$mboh_jml_salah = mysql_num_rows($qmboh3);
			
			
			
			//total siswa
			$mboh_total = $mboh_jml_benar + $mboh_jml_salah;
			
			//nilai
			$mboh_nilai = round(($mboh_jml_benar / $mboh_total) * 100,2);
			
			
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>Nomor Soal '.$i_no.'</td>
			<td>'.$mboh_total.'</td>
			<td>'.$mboh_jml_benar.'</td>
			<td>'.$mboh_jml_salah.'</td>
			<td>'.$mboh_nilai.'</td>
			</tr>';
			}
		while ($data = mysql_fetch_assoc($result));
		}
	
	
	echo '</tbody>
	  </table>
	  </div>
	
	
	<table width="500" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<td>
	
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	</td>
	</tr>
	</table>
	</form>';
	
	
	
	
	
	//isi
	$isi = ob_get_contents();
	ob_end_clean();
	
	require("../../inc/niltpl.php");
	}





//null-kan
xclose($koneksi);
exit();
?>