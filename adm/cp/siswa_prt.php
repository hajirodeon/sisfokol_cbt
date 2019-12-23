<?php
session_start();


//ambil nilai
require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/class/kartu_ujian.php");



nocache;

//nilai
$filenya = "siswa_prt.php";
$judul = "Print Kartu Tes";
$judulku = $judul;
$ku_judul = $judulku;
$s = nosql($_REQUEST['s']);
$kdx = nosql($_REQUEST['ckd']);
$kd = nosql($_REQUEST['ckd']);







//start class
$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle($judul);
$pdf->SetAuthor($author);
$pdf->SetSubject($description);
$pdf->SetKeywords($keywords);







//profil
$qx = mysql_query("SELECT * FROM siswa ".
					"WHERE kd = '$kdx'");
$rowx = mysql_fetch_assoc($qx);
$tx = mysql_num_rows($qx);
$e_nis = balikin($rowx['nis']);
$e_nisn = balikin($rowx['nisn']);
$e_nama = balikin($rowx['nama']);
$e_user = balikin($rowx['usernamex']);
$e_pass = balikin($rowx['passwordx2']);
$e_kelas = balikin($rowx['kelas']);
$e_lahir_tmp = balikin($rowx['lahir_tmp']);
$e_lahir_tgl = balikin($rowx['lahir_tgl']);
$e_user = balikin($rowx['username']);
$e_passx2 = balikin($rowx['passwordx2']);





//jika null
if (empty($e_pass))
	{
	//pass
	$passku = substr($x,0,5);
	$passkux = md5($passku);
	}
//jika ada
else 
	{
	$passku = $e_pass;
	$passkux = md5($e_pass);	
	}	



//bikin user
mysql_query("UPDATE siswa SET usernamex = '$e_nis', ".
				"passwordx = '$passkux', ".
				"passwordx2 = '$passku', ".
				"aktif = 'true', ".
				"aktif_postdate = '$today' ".
				"WHERE kd = '$kdx'");






//profil
$qx = mysql_query("SELECT * FROM siswa ".
					"WHERE kd = '$kdx'");
$rowx = mysql_fetch_assoc($qx);
$tx = mysql_num_rows($qx);
$e_nis = balikin($rowx['nis']);
$e_nisn = balikin($rowx['nisn']);
$e_nama = balikin($rowx['nama']);
$e_user = balikin($rowx['usernamex']);
$e_pass = balikin($rowx['passwordx2']);
$e_kelas = balikin($rowx['kelas']);
$e_lahir_tmp = balikin($rowx['lahir_tmp']);
$e_lahir_tgl = balikin($rowx['lahir_tgl']);
$e_user = balikin($rowx['username']);
$e_passx2 = balikin($rowx['passwordx2']);








//image
$pdf-> Image('../../img/logo.jpg',11,11,8); //logo





//bikin kotak garis luar
$pdf->Cell(70,50,'',1,0,'L');



$baris_tebal = 5;
$pdf->SetY(10);
$pdf->SetX(20);
$pdf->SetFont('Times','B',10);
$pdf->Cell(70,$baris_tebal,'KARTU UJIAN',0,0,'L');
$pdf->SetY(10+$baris_tebal);
$pdf->SetX(20);
$pdf->Cell(70,$baris_tebal,$sek_nama,0,0,'L');

//garis
$pdf->Ln();
$baris_tebal2 = 0.1;
$pdf->Cell(70,$baris_tebal2,'',1,0,'C');
					

//set posisi
$pdf->SetY(10+(3 * $baris_tebal));

$pdf->SetFont('Times','',10);
$pdf->Cell(20,5,'NIS ',0,0,'L');
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,5,': '.$e_nis.'',0,0,'L');
$pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->Cell(20,5,'NISN ',0,0,'L');
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,5,': '.$e_nisn.'',0,0,'L');
$pdf->Ln();


$pdf->SetFont('Times','',10);
$pdf->Cell(20,5,'Nama ',0,0,'L');
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,5,': '.$e_nama.'',0,0,'L');
$pdf->Ln();


$pdf->SetFont('Times','',10);
$pdf->Cell(20,5,'Kelas ',0,0,'L');
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,5,': '.$e_kelas.'',0,0,'L');
$pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->Cell(20,5,'Username ',0,0,'L');
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,5,': '.$e_nis.'',0,0,'L');
$pdf->Ln();

$pdf->SetFont('Times','',10);
$pdf->Cell(20,5,'Password ',0,0,'L');
$pdf->SetFont('Times','B',10);
$pdf->Cell(30,5,': '.$e_passx2.'',0,0,'L');
$pdf->Ln();








//output-kan ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$pdf->Output("siswa-$e_nis.pdf",I);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>
