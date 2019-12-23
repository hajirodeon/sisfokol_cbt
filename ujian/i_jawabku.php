<?php
session_start();

require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
require("../inc/class/paging.php");

	

$limit = 50;


$filenyax = "$sumber/ujian/i_jawabku.php";




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika form
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'form'))
	{
	sleep(1);
	
	//ambil nilai
	$skd = trim(cegah($_GET['skd']));
	$jkd = trim(cegah($_GET['jkd']));
	$sesiku = $skd;
	
	$tablenya = "siswaujian$sesiku";
	$tablenilai = "siswanilai$sesiku";


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
		$yuk_kdku = nosql($ryuk['kd']);
		$yuk_jawabku = balikin($ryuk['jawab']);
		
		
		
		
		echo '<a href="#ku'.$i_kd.'"><b>'.$i_no.'</b> <span class="badge">'.$yuk_jawabku.'</span></a>, ';
		}
	while ($data = mysql_fetch_assoc($result));
	
		
	
	
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