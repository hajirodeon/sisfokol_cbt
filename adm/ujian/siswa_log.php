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
$filenya = "siswa_log.php";
$judul = "[SISWA] LOG Pengerjaan Siswa";
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



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika ke daftar
if ($_POST['btnDF'])
	{
	//re-direct
	$ke = "siswa.php";
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
	$i_filename = seo_friendly_url("logujian-$u_waktu-$u_mapel-$u_tingkat.xls");
	$i_judul = "LOG";
	



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
	$worksheet1->write_string(0,1,"POSTDATE");
	$worksheet1->write_string(0,2,"SISWA");
	$worksheet1->write_string(0,3,"SOAL");
	$worksheet1->write_string(0,4,"JAWAB");


	//query	
	$qyukx = mysql_query("SELECT * FROM siswa_soal ".
							"WHERE jadwal_kd LIKE '$jkd' ".
							"ORDER BY postdate DESC");
	$ryukx = mysql_fetch_assoc($qyukx);

	do 
		{
		$dt_nox = $dt_nox + 1;
		$i_kd = nosql($ryukx['kd']);
		$i_jkd = nosql($ryukx['jadwal_kd']);
		$i_postdate = balikin($ryukx['postdate']);
		$i_swkd = nosql($ryukx['siswa_kd']);
		$i_skd = balikin($ryukx['soal_kd']);
		$i_jawab = balikin($ryukx['jawab']);
		
		
		//detail siswa
		$qyuk = mysql_query("SELECT * FROM siswa ".
								"WHERE kd = '$i_swkd'");
		$ryuk = mysql_fetch_assoc($qyuk);
		$yuk_nis = balikin($ryuk['nis']);
		$yuk_nama = balikin($ryuk['nama']);
		$yuk_kelas = balikin($ryuk['kelas']);

		
		
		
		//detail soal
		$qyuk2 = mysql_query("SELECT * FROM m_soal ".
								"WHERE kd = '$i_skd'");
		$ryuk2 = mysql_fetch_assoc($qyuk2);
		$yuk2_no = balikin($ryuk2['no']);
		
		
		
		
		
		//cek dari table siswa
		$tableku = "siswanilai$i_swkd";
		$qyuk2x = mysql_query("SELECT * FROM $tableku ".
								"ORDER BY postdate DESC");
		$ryuk2x = mysql_fetch_assoc($qyuk2x);
		$yuk2x_jkd = balikin($ryuk2x['jadwal_kd']);
		
		
		
		//detail jkd jadwal
		$qku = mysql_query("SELECT * FROM m_jadwal ".
								"WHERE kd = '$yuk2x_jkd'");
		$rku = mysql_fetch_assoc($qku);
		$u_waktu = balikin($rku['waktu']);
		$u_pukul = balikin($rku['pukul']);
		$u_durasi = balikin($rku['durasi']);
		$u_mapel = balikin($rku['mapel']);
		$u_tingkat = balikin($rku['tingkat']);
		$u_soal_jml = balikin($rku['soal_jml']);
		$u_postdate_mulai = balikin($rku['postdate_mulai']);
		$u_postdate_selesai = balikin($rku['postdate_selesai']);

		
		
		//update
		mysql_query("UPDATE siswa_soal SET jadwal_kd = '$yuk2x_jkd' ".
						"WHERE kd = '$i_kd'");		
		
		
		$i_waktu = "$u_waktu, $u_pukul, $u_durasi Menit, $u_mapel, $u_tingkat";
		$i_siswa = "$yuk_nis. $yuk_nama.";
		$i_soalno = "$yuk2_no.";
		
		
		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_nox);
		$worksheet1->write_string($dt_nox,1,$i_postdate);
		$worksheet1->write_string($dt_nox,2,$i_siswa);
		$worksheet1->write_string($dt_nox,3,$i_soalno);
		$worksheet1->write_string($dt_nox,4,$i_jawab);
		}
	while ($ryukx = mysql_fetch_assoc($qyukx));
		
	
	//close
	$workbook->close();

	
	
	//re-direct
	//exit
	mysql_free_result();
	xclose($koneksi);
	xloc($filenya);
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
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlcount = "SELECT * FROM siswa_soal ".
					"WHERE jadwal_kd = '$jkd' ".
					"ORDER BY postdate DESC";
	
	$sqlresult = $sqlcount;
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$target = "$filenya?jkd=$jkd";
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);
	
	
	
	
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
	
	
	
	
	
	//ketahui jumlah siswa yg mengerjakan
	$qjos = mysql_query("SELECT DISTINCT(siswa_kd) AS skd ".
							"FROM siswa_soal ".
							"WHERE jadwal_kd = '$jkd'");
	$tjos = mysql_num_rows($qjos);
	
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
	
	
	
	<form action="'.$filenya.'" method="post" name="formx">
	
	
	[Siswa yang mengerjakan : <b>'.$tjos.'</b>]. 
	<a href="'.$filenya.'?s=excel&jkd='.$jkd.'" class="btn btn-success">EXPORT EXCEL >></a>
	
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td width="50"><strong><font color="'.$warnatext.'">POSTDATE</font></strong></td>
	<td><strong><font color="'.$warnatext.'">SISWA</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">SOAL</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">JAWAB</font></strong></td>
	
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
			$i_postdate = balikin($data['postdate']);
			$i_swkd = nosql($data['siswa_kd']);
			$i_skd = balikin($data['soal_kd']);
			$i_jawab = balikin($data['jawab']);
			
			
			//detail siswa
			$qyuk = mysql_query("SELECT * FROM siswa ".
									"WHERE kd = '$i_swkd'");
			$ryuk = mysql_fetch_assoc($qyuk);
			$yuk_nis = balikin($ryuk['nis']);
			$yuk_nama = balikin($ryuk['nama']);
	
			
			
			
			//detail soal
			$qyuk2 = mysql_query("SELECT * FROM m_soal ".
									"WHERE kd = '$i_skd'");
			$ryuk2 = mysql_fetch_assoc($qyuk2);
			$yuk2_no = balikin($ryuk2['no']);
			
			
			
			
			
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_postdate.'</td>
			<td>
			'.$yuk_nis.'. '.$yuk_nama.'. 
			</td>
			<td>
			Nomor '.$yuk2_no.'
			
			</td>
			<td>'.$i_jawab.'</td>
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
	<strong><font color="#FF0000">'.$count.'</font></strong> Data. '.$pagelist.'
	<br>
	<input name="jkd" type="hidden" value="'.$jkd.'">
	
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