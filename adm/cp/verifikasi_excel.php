<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");


nocache;



//nilai
$s = nosql($_REQUEST['s']);


//require
require('../../inc/class/excel/OLEwriter.php');
require('../../inc/class/excel/BIFFwriter.php');
require('../../inc/class/excel/worksheet.php');
require('../../inc/class/excel/workbook.php');





//jika belum verifikasi ////////////////////////////////////////////////////////////////////////////////
if ($s == "belum")
	{	
	//nama file e...
	$i_filename = "belum_verifikasi.xls";
	$i_judul = "BELUM";
	
	
	
	
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
	$worksheet1->write_string(0,1,"NIS");
	$worksheet1->write_string(0,2,"NISN");
	$worksheet1->write_string(0,3,"NAMA");
	$worksheet1->write_string(0,4,"KELAS");
	$worksheet1->write_string(0,5,"LAHIR_TMP");
	$worksheet1->write_string(0,6,"LAHIR_TGL");
	
	//data
	$qdt = mysql_query("SELECT * FROM siswa ".
						"WHERE aktif = 'false' ".
						"ORDER BY kelas ASC, ".
						"round(nis) ASC");
	$rdt = mysql_fetch_assoc($qdt);
	
	do
		{
		//nilai
		$dt_nox = $dt_nox + 1;
		$dt_nis = balikin($rdt['nis']);
		$dt_nisn = balikin($rdt['nisn']);
		$dt_nama = balikin($rdt['nama']);
		$dt_kelas = balikin($rdt['kelas']);
		$dt_kelamin = balikin($rdt['kelamin']);
		$dt_lahir_tmp = balikin($rdt['lahir_tmp']);
		$dt_lahir_tgl = balikin($rdt['lahir_tgl']);
	
	
		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_nox);
		$worksheet1->write_string($dt_nox,1,$dt_nis);
		$worksheet1->write_string($dt_nox,2,$dt_nisn);
		$worksheet1->write_string($dt_nox,3,$dt_nama);
		$worksheet1->write_string($dt_nox,4,$dt_kelas);
		$worksheet1->write_string($dt_nox,5,$dt_lahir_tmp);
		$worksheet1->write_string($dt_nox,6,$dt_lahir_tgl);
		}
	while ($rdt = mysql_fetch_assoc($qdt));
	
	
	//close
	$workbook->close();
	}







//jika sudah verifikasi ////////////////////////////////////////////////////////////////////////////////
else if ($s == "sudah")
	{	
	//nama file e...
	$i_filename = "sudah_verifikasi.xls";
	$i_judul = "SUDAH";
	
	
	
	
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
	$worksheet1->write_string(0,2,"USERNAME");
	$worksheet1->write_string(0,3,"PASSWORD");
	$worksheet1->write_string(0,4,"NIS");
	$worksheet1->write_string(0,5,"NISN");
	$worksheet1->write_string(0,6,"NAMA");
	$worksheet1->write_string(0,7,"KELAS");
	$worksheet1->write_string(0,8,"LAHIR_TMP");
	$worksheet1->write_string(0,9,"LAHIR_TGL");
	
	//data
	$qdt = mysql_query("SELECT * FROM siswa ".
						"WHERE aktif = 'true' ".
						"ORDER BY aktif_postdate DESC");
	$rdt = mysql_fetch_assoc($qdt);
	
	do
		{
		//nilai
		$dt_nox = $dt_nox + 1;
		$dt_aktif_postdate = balikin($rdt['aktif_postdate']);
		$dt_user = balikin($rdt['usernamex']);
		$dt_pass = balikin($rdt['passwordx2']);
		$dt_nis = balikin($rdt['nis']);
		$dt_nisn = balikin($rdt['nisn']);
		$dt_nama = balikin($rdt['nama']);
		$dt_kelas = balikin($rdt['kelas']);
		$dt_kelamin = balikin($rdt['kelamin']);
		$dt_lahir_tmp = balikin($rdt['lahir_tmp']);
		$dt_lahir_tgl = balikin($rdt['lahir_tgl']);
	
	
		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_nox);
		$worksheet1->write_string($dt_nox,1,$dt_aktif_postdate);
		$worksheet1->write_string($dt_nox,2,$dt_user);
		$worksheet1->write_string($dt_nox,3,$dt_pass);
		$worksheet1->write_string($dt_nox,4,$dt_nis);
		$worksheet1->write_string($dt_nox,5,$dt_nisn);
		$worksheet1->write_string($dt_nox,6,$dt_nama);
		$worksheet1->write_string($dt_nox,7,$dt_kelas);
		$worksheet1->write_string($dt_nox,8,$dt_lahir_tmp);
		$worksheet1->write_string($dt_nox,9,$dt_lahir_tgl);
		}
	while ($rdt = mysql_fetch_assoc($qdt));
	
	
	//close
	$workbook->close();
	}






//null-kan
xclose($koneksi);
exit();
?>