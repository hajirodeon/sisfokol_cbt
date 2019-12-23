<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/admin.html");

nocache;





//ketahui tapel terakhir
$qmboh = mysql_query("SELECT * FROM psb_m_tapel ".
						"WHERE aktif = 'true' ".
						"ORDER BY tahun1 DESC");
$rmboh = mysql_fetch_assoc($qmboh);
$tapelkd = nosql($rmboh['kd']);
$tahun1 = nosql($rmboh['tahun1']);
$tahun2 = nosql($rmboh['tahun2']);



$limit = 100;



//nilai
$filenya = "siswa.php";
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);

$judul = "[MASTER] Data Siswa";
$judulku = "$judul";
$judulx = $judul;
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}



//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//nek batal
if ($_POST['btnBTL'])
	{
	//exit
	mysql_free_result();
	xclose($koneksi);
	
	//re-direct
	xloc($filenya);
	exit();
	}



//jika import
if ($_POST['btnIM'])
	{
	//exit
	mysql_free_result();
	xclose($koneksi);
	
	//re-direct
	$ke = "$filenya?s=import";
	xloc($ke);
	exit();
	}






//import sekarang
if ($_POST['btnIMX'])
	{
	$filex_namex2 = strip(strtolower($_FILES['filex_xls']['name']));

	//nek null
	if (empty($filex_namex2))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		$ke = "$filenya?s=import";
		pekem($pesan,$ke);
		
		//exit
		mysql_free_result();
		xclose($koneksi);		
		exit();
		}
	else
		{
		//deteksi .xls
		$ext_filex = substr($filex_namex2, -4);

		if ($ext_filex == ".xls")
			{
			//nilai
			$path1 = "../../filebox";
			$path2 = "../../filebox/excel";
			chmod($path1,0777);
			chmod($path2,0777);

			//nama file import, diubah menjadi baru...
			$filex_namex2 = "pegawai.xls";

			//mengkopi file
			copy($_FILES['filex_xls']['tmp_name'],"../../filebox/excel/$filex_namex2");

			//chmod
            $path3 = "../../filebox/excel/$filex_namex2";
			chmod($path1,0755);
			chmod($path2,0777);
			chmod($path3,0777);

			//file-nya...
			$uploadfile = $path3;


			//require
			require('../../inc/class/PHPExcel.php');
			require('../../inc/class/PHPExcel/IOFactory.php');


			  // load excel
			  $load = PHPExcel_IOFactory::load($uploadfile);
			  $sheets = $load->getActiveSheet()->toArray(null,true,true,true);
			
			  $i = 1;
			  foreach ($sheets as $sheet) 
			  	{
			    // karena data yang di excel di mulai dari baris ke 2
			    // maka jika $i lebih dari 1 data akan di masukan ke database
			    if ($i > 1) 
			    	{
				      // nama ada di kolom A
				      // sedangkan alamat ada di kolom B
				      $o_xyz = md5("$x$i");
				      $o_no = cegah($sheet['A']);
				      $o_nis = cegah($sheet['B']);
				      $o_nisn = cegah($sheet['C']);
				      $o_nama = cegah($sheet['D']);
				      $o_kelas = cegah($sheet['E']);
				      $o_lahir_tmp = cegah($sheet['F']);
				      $o_lahir_tgl = cegah($sheet['G']);
				      $o_kelamin = cegah($sheet['H']);
					  
					  
	
	
						//cek
						$qcc = mysql_query("SELECT * FROM siswa ".
												"WHERE nis = '$i_nis'");
						$rcc = mysql_fetch_assoc($qcc);
						$tcc = mysql_num_rows($qcc);
		
						//jika ada, update				
						if (!empty($tcc))
							{
							mysql_query("UPDATE siswa SET nisn = '$o_nisn', ".
											"nama = '$o_nama', ".
											"kelas = '$o_kelas', ".
											"kelamin = '$o_kelamin', ".
											"lahir_tmp = '$o_lahir_tmp', ".
											"lahir_tgl = '$o_lahir_tgl' ".
											"WHERE nis = '$o_nis'");
							}
		
		
						else
							{
							//insert
							mysql_query("INSERT INTO siswa(kd, tapel_kd, nis, nisn, nama, kelas, ".
											"kelamin, lahir_tmp, lahir_tgl, postdate) VALUES ".
											"('$o_xyz', '$tapelkd', '$o_nis', '$o_nisn', '$o_nama', '$o_kelas', ".
											"'$o_kelamin', '$o_lahir_tmp', '$o_lahir_tgl', '$today')");
							}
					  
				    }
			
			    $i++;
			  }





			//hapus file, jika telah import
			$path1 = "../../filebox/excel/$filex_namex2";
			chmod($path1,0777);
			unlink ($path1);


			//re-direct
			mysql_free_result();
			xclose($koneksi);
			xloc($filenya);
			exit();
			}
		else
			{
			//exit
			mysql_free_result();
			xclose($koneksi);
				
			//salah
			$pesan = "Bukan File .xls . Harap Diperhatikan...!!";
			$ke = "$filenya?s=import";
			pekem($pesan,$ke);
			exit();
			}
		}
	}








//jika export
//export
if ($_POST['btnEX'])
	{
	//require
	require('../../inc/class/excel/OLEwriter.php');
	require('../../inc/class/excel/BIFFwriter.php');
	require('../../inc/class/excel/worksheet.php');
	require('../../inc/class/excel/workbook.php');


	//nama file e...
	$i_filename = "siswa-$tahun1-$tahun2.xls";
	$i_judul = "SISWA";
	



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
	$worksheet1->write_string(0,7,"KELAMIN");
	$worksheet1->write_string(0,8,"VERIFIKASI");

	//data
	$qdt = mysql_query("SELECT * FROM siswa ".
							"WHERE tapel_kd = '$tapelkd' ".
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
		$dt_aktif = balikin($rdt['aktif']);

		//jika belum aktif, perlu verifikasi
		if ($dt_aktif == "false")
			{
			$aktif_ket = "Belum Verifikasi";
			}
			
		else if ($dt_aktif == "true")
			{
			$aktif_ket = "AKTIF";
			}
	
	
		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_nox);
		$worksheet1->write_string($dt_nox,1,$dt_nis);
		$worksheet1->write_string($dt_nox,2,$dt_nisn);
		$worksheet1->write_string($dt_nox,3,$dt_nama);
		$worksheet1->write_string($dt_nox,4,$dt_kelas);
		$worksheet1->write_string($dt_nox,5,$dt_lahir_tmp);
		$worksheet1->write_string($dt_nox,6,$dt_lahir_tgl);
		$worksheet1->write_string($dt_nox,7,$dt_kelamin);
		$worksheet1->write_string($dt_nox,8,$aktif_ket);
		}
	while ($rdt = mysql_fetch_assoc($qdt));


	//close
	$workbook->close();

	
	
	//re-direct
	//exit
	mysql_free_result();
	xclose($koneksi);
	xloc($filenya);
	exit();
	}











//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kunci = cegah($_POST['kunci']);


	//re-direct
	//exit
	mysql_free_result();
	xclose($koneksi);
	$ke = "$filenya?kunci=$kunci";
	xloc($ke);
	exit();
	}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



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
//jika import
if ($s == "import")
	{
	?>
	<div class="row">

	<div class="col-md-12">
		
	<?php
	echo '<form action="'.$filenya.'" method="post" enctype="multipart/form-data" name="formxx2">
	<p>
		<input name="filex_xls" type="file" size="30" class="btn btn-warning">
	</p>

	<p>
		<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
		<input name="btnIMX" type="submit" value="IMPORT >>" class="btn btn-danger">
	</p>
	
	
	</form>';	
	?>
		


	</div>
	
	</div>


	<?php
	}


else
	{
	echo '<form action="'.$filenya.'" method="post" name="formxx">';
		
	//jika null
	if (empty($kunci))
		{
		$sqlcount = "SELECT * FROM siswa ".
						"WHERE tapel_kd = '$tapelkd' ".
						"ORDER BY aktif_postdate DESC";
		}
		
	else
		{
		$sqlcount = "SELECT * FROM siswa ".
						"WHERE tapel_kd = '$tapelkd' ".
						"AND (nis LIKE '%$kunci%' ".
						"OR nisn LIKE '%$kunci%' ".
						"OR nama LIKE '%$kunci%' ".
						"OR kelas LIKE '%$kunci%' ".
						"OR kelamin LIKE '%$kunci%' ".
						"OR lahir_tmp LIKE '%$kunci%' ".
						"OR lahir_tgl LIKE '%$kunci%' ".
						"OR postdate LIKE '%$kunci%') ".
						"ORDER BY aktif_postdate DESC";
		}
		
	
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlresult = $sqlcount;
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$target = "$filenya?tapelkd=$tapelkd";
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);
	
	
	//ketahui jumlahnya
	$qyo = mysql_query("SELECT * FROM siswa ". 
							"WHERE tapel_kd = '$tapelkd'");
	$ryo = mysql_fetch_assoc($qyo);
	$tyo = mysql_num_rows($qyo);
	
	
	
	
	
	echo '<hr>
	<p>
	<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning">
	<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
	<input name="btnIM" type="submit" value="IMPORT EXCEL" class="btn btn-primary">
	<input name="btnEX" type="submit" value="EXPORT EXCEL" class="btn btn-success">
	<input name="s" type="hidden" value="'.$s.'">
	
	</p>
		
	
	
	[TOTAL : <b>'.$tyo.'</b>].
	
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td width="50"><strong><font color="'.$warnatext.'">VERIFIKASI</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">NIS</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">NISN</font></strong></td>
	<td><strong><font color="'.$warnatext.'">NAMA</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">KELAS</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">KELAMIN</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">LAHIR</font></strong></td>
	
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
			$i_nis = balikin($data['nis']);
			$i_nisn = balikin($data['nisn']);
			$i_nama = balikin($data['nama']);
			$i_kelas = balikin($data['kelas']);
			$i_kelamin = balikin($data['kelamin']);
			$i_lahir_tmp = balikin($data['lahir_tmp']);
			$i_lahir_tgl = balikin($data['lahir_tgl']);
			$i_aktif = balikin($data['aktif']);
			$i_aktif_postdate = balikin($data['aktif_postdate']);



			//bikin table khusus siswaujian_siswa_kd /////////////////////////////////////////////////
			$tablenya = "siswaujian$i_kd";
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
			
			//bikin table khusus siswaujian_siswa_kd /////////////////////////////////////////////////

			
	
	


			//bikin table khusus siswanilai_siswa_kd /////////////////////////////////////////////////
			$tableku = "siswanilai$i_kd";
		
			mysql_query("CREATE TABLE IF NOT EXISTS $tableku (
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

						
			mysql_query("ALTER TABLE $tableku ADD PRIMARY KEY (`kd`);");
			//bikin table khusus siswanilai_siswa_kd /////////////////////////////////////////////////




			
			
			 
 
		
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>'.$i_aktif_postdate.'</td>
			<td>'.$i_nis.'</td>
			<td>'.$i_nisn.'</td>
			<td>
			'.$i_nama.'
			</td>
			<td>
			'.$i_kelas.'
			</td>
			
			<td>'.$i_kelamin.'</td>
			<td>'.$i_lahir_tmp.', '.$i_lahir_tgl.'</td>
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
	
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	</td>
	</tr>
	</table>
	</form>';
	}




//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");


//null-kan
mysql_free_result();
xclose($koneksi);
exit();
?>