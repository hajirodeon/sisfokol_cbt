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
$filenya = "jadwal.php";
$judul = "[MASTER] Data Jadwal";
$judulku = "$judul";
$judulx = $judul;
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
//jika import
if ($_POST['btnIM'])
	{
	//re-direct
	$ke = "$filenya?s=import";
	xloc($ke);
	exit();
	}












//lama
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
				      $o_waktu = cegah($sheet['B']);
				      $o_pukul = cegah($sheet['C']);
				      $o_durasi = cegah($sheet['D']);
				      $o_mapel = cegah($sheet['E']);
				      $o_tingkat = cegah($sheet['F']);
					  
						//cek
						$qcc = mysql_query("SELECT * FROM m_jadwal ".
												"WHERE no = '$o_no' ".
												"AND waktu = '$o_waktu' ".
												"AND pukul = '$o_pukul'");
						$rcc = mysql_fetch_assoc($qcc);
						$tcc = mysql_num_rows($qcc);
		
						//jika ada, update				
						if (!empty($tcc))
							{
							mysql_query("UPDATE m_jadwal SET waktu = '$o_waktu', ".
											"durasi = '$o_durasi', ".
											"mapel = '$o_mapel', ".
											"tingkat = '$o_tingkat' ".
											"WHERE no = '$o_no' ".
											"AND waktu = '$o_waktu' ".
											"AND pukul = '$o_pukul'");
							}
		
		
						else
							{
							//insert
							mysql_query("INSERT INTO m_jadwal(kd, no, waktu, pukul, durasi, mapel, tingkat, postdate) VALUES ".
											"('$o_xyz', '$o_no', '$o_waktu', '$o_pukul', '$o_durasi', '$o_mapel', '$o_tingkat', '$today')");
							}
					  
				    }
			
			    $i++;
			  }





			//re-direct
			xloc($filenya);
			exit();
			}
		else
			{
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
	$i_filename = "jadwal.xls";
	$i_judul = "Jadwal";
	



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
	$worksheet1->write_string(0,1,"WAKTU");
	$worksheet1->write_string(0,2,"PUKUL");
	$worksheet1->write_string(0,3,"DURASI");
	$worksheet1->write_string(0,4,"MAPEL");
	$worksheet1->write_string(0,5,"TINGKAT");



	//data
	$qdt = mysql_query("SELECT * FROM m_jadwal ".
							"ORDER BY round(no) ASC, ".
							"waktu ASC, ".
							"pukul ASC");
	$rdt = mysql_fetch_assoc($qdt);

	do
		{
		//nilai
		$dt_nox = $dt_nox + 1;
		$dt_no = balikin($rdt['no']);
		$dt_waktu = balikin($rdt['waktu']);
		$dt_pukul = balikin($rdt['pukul']);
		$dt_durasi = balikin($rdt['durasi']);
		$dt_mapel = balikin($rdt['mapel']);
		$dt_tingkat = balikin($rdt['tingkat']);



		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_no);
		$worksheet1->write_string($dt_nox,1,$dt_waktu);
		$worksheet1->write_string($dt_nox,2,$dt_pukul);
		$worksheet1->write_string($dt_nox,3,$dt_durasi);
		$worksheet1->write_string($dt_nox,4,$dt_mapel);
		$worksheet1->write_string($dt_nox,5,$dt_tingkat);
		}
	while ($rdt = mysql_fetch_assoc($qdt));


	//close
	$workbook->close();

	
	
	//re-direct
	xloc($filenya);
	exit();
	}








//nek batal
if ($_POST['btnBTL'])
	{
	//re-direct
	xloc($filenya);
	exit();
	}





//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?kunci=$kunci";
	xloc($ke);
	exit();
	}




//nek entri baru
if ($_POST['btnBARU'])
	{
	//re-direct
	$ke = "$filenya?s=baru&kd=$x";
	xloc($ke);
	exit();
	}







//jika simpan
if ($_POST['btnSMP'])
	{
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$e_no = cegah($_POST['e_no']);
	$e_waktu = cegah($_POST['e_waktu']);
	$e_pukul = cegah($_POST['e_pukul']);
	$e_durasi = cegah($_POST['e_durasi']);
	$e_mapel = cegah($_POST['e_mapel']);
	$e_tingkat = cegah($_POST['e_tingkat']);



	//nek null
	if ((empty($e_no)) OR (empty($e_waktu)) OR (empty($e_pukul)) OR (empty($e_durasi)) OR (empty($e_mapel)) OR (empty($e_tingkat)))
		{
		//re-direct
		$pesan = "Belum Ditulis. Harap Diulangi...!!";
		$ke = "$filenya?s=$s&kd=$kd";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//jika update
		if ($s == "edit")
			{
			mysql_query("UPDATE m_jadwal SET no = '$e_no', ".
							"waktu = '$e_waktu', ".
							"pukul = '$e_pukul', ".
							"durasi = '$e_durasi', ".
							"mapel = '$e_mapel', ".
							"tingkat = '$e_tingkat' ".
							"WHERE kd = '$kd'");

			//re-direct
			xloc($filenya);
			exit();
			}



		//jika baru
		if ($s == "baru")
			{
			/*
			//cek
			$qcc = mysql_query("SELECT * FROM m_jadwal ".
									"WHERE no = '$e_no' ".
									"AND waktu = '$e_waktu' ".
									"AND pukul = '$e_pukul' ".
									"AND tingkat = '$e_tingkat'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//re-direct
				$pesan = "Sudah Ada. Silahkan Ganti Yang Lain...!!";
				$ke = "$filenya?s=baru&kd=$kd";
				pekem($pesan,$ke);
				exit();
				}
			else
				{
				mysql_query("INSERT INTO m_jadwal(kd, no, waktu, pukul, durasi, mapel, tingkat, postdate) VALUES ".
								"('$kd', '$e_no', '$e_waktu', '$e_pukul', '$e_durasi', '$e_mapel', '$e_tingkat', '$today')");

								
				//re-direct
				xloc($filenya);
				exit();
				}
			 * 
			 */
			 
			mysql_query("INSERT INTO m_jadwal(kd, no, waktu, pukul, durasi, mapel, tingkat, postdate) VALUES ".
							"('$kd', '$e_no', '$e_waktu', '$e_pukul', '$e_durasi', '$e_mapel', '$e_tingkat', '$today')");

							
			//re-direct
			xloc($filenya);
			exit();
			}
		}
	}




//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);
	$ke = "$filenya?page=$page";

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysql_query("DELETE FROM m_jadwal ".
						"WHERE kd = '$kd'");
		}

	//auto-kembali
	xloc($filenya);
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
//jika edit / baru
if (($s == "baru") OR ($s == "edit"))
	{
	$kdx = nosql($_REQUEST['kd']);

	$qx = mysql_query("SELECT * FROM m_jadwal ".
						"WHERE kd = '$kdx'");
	$rowx = mysql_fetch_assoc($qx);
	$e_no = balikin($rowx['no']);
	$e_waktu = balikin($rowx['waktu']);
	$e_pukul = balikin($rowx['pukul']);
	$e_durasi = balikin($rowx['durasi']);
	$e_mapel = balikin($rowx['mapel']);
	$e_tingkat = balikin($rowx['tingkat']);


		
			?>
	
	
	
	<div class="row">

	<div class="col-md-6">
		
	<?php
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	
	
	<p>
	No : 
	<br>
	<input name="e_no" type="text" value="'.$e_no.'" size="5" class="btn-warning">
	</p>
	
	
	
	<p>
	Waktu : 
	<br>
	<input name="e_waktu" type="text" value="'.$e_waktu.'" size="50" class="btn-warning">
	</p>
	
	
	<p>
	Pukul : 
	<br>
	<input name="e_pukul" type="text" value="'.$e_pukul.'" size="50" class="btn-warning">
	</p>
	
	
	
	<p>
	Durasi : 
	<br>
	<input name="e_durasi" type="text" value="'.$e_durasi.'" size="5" class="btn-warning"> Menit
	</p>
	
	
	
	<p>
	Mapel : 
	<br>
	<input name="e_mapel" type="text" value="'.$e_mapel.'" size="50" class="btn-warning">
	</p>
	
	<p>
	Tingkat Kelas : 
	<br>
	<input name="e_tingkat" type="text" value="'.$e_tingkat.'" size="10" class="btn-warning">
	</p>
	
	
	<p>
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	
	<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
	</p>
	
	
	</form>';
	
	?>
		
	
	
	</div>




	</div>


	<?php
	}
	









//jika import
else if ($s == "import")
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
	//jika null
	if (empty($kunci))
		{
		$sqlcount = "SELECT * FROM m_jadwal ".
						"ORDER BY round(no) ASC, ".
						"pukul ASC";
		}
		
	else
		{
		$sqlcount = "SELECT * FROM m_jadwal ".
						"WHERE waktu LIKE '%$kunci%' ".
						"OR durasi LIKE '%$kunci%' ".
						"OR mapel LIKE '%$kunci%' ".
						"OR tingkat LIKE '%$kunci%' ".
						"ORDER BY round(no) ASC, ".
						"pukul ASC";
		}
		
		
	
	//query
	$p = new Pager();
	$start = $p->findStart($limit);
	
	$sqlresult = $sqlcount;
	
	$count = mysql_num_rows(mysql_query($sqlcount));
	$pages = $p->findPages($count, $limit);
	$result = mysql_query("$sqlresult LIMIT ".$start.", ".$limit);
	$pagelist = $p->pageList($_GET['page'], $pages, $target);
	$data = mysql_fetch_array($result);
	
	
	
	echo '<form action="'.$filenya.'" method="post" name="formxx">
	<p>
	<input name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-danger">
	<input name="btnIM" type="submit" value="IMPORT" class="btn btn-primary">
	<input name="btnEX" type="submit" value="EXPORT" class="btn btn-success">
	</p>
	<br>
	
	</form>



	<form action="'.$filenya.'" method="post" name="formx">
	<p>
	<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning" placeholder="Kata Kunci...">
	<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
	</p>
		
	
	<div class="table-responsive">          
	<table class="table" border="1">
	<thead>
	
	<tr valign="top" bgcolor="'.$warnaheader.'">
	<td width="20">&nbsp;</td>
	<td width="20">&nbsp;</td>
	<td width="50"><strong><font color="'.$warnatext.'">NO</font></strong></td>
	<td><strong><font color="'.$warnatext.'">WAKTU</font></strong></td>
	<td width="150"><strong><font color="'.$warnatext.'">PUKUL</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">DURASI</font></strong></td>
	<td><strong><font color="'.$warnatext.'">MAPEL</font></strong></td>
	<td width="100"><strong><font color="'.$warnatext.'">TINGKAT</font></strong></td>
	
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
			$i_waktu = balikin($data['waktu']);
			$i_pukul = balikin($data['pukul']);
			$i_durasi = balikin($data['durasi']);
			$i_mapel = balikin($data['mapel']);
			$i_tingkat = balikin($data['tingkat']);

			
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
	        </td>
			<td>
			<a href="'.$filenya.'?s=edit&page='.$page.'&kd='.$i_kd.'"><img src="'.$sumber.'/template/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			<td>'.$i_no.'</td>
			<td>'.$i_waktu.'</td>
			<td>'.$i_pukul.'</td>
			<td>'.$i_durasi.'</td>
			<td>'.$i_mapel.'</td>
			<td>'.$i_tingkat.'</td>
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
	
	<input name="btnALL" type="button" value="SEMUA" onClick="checkAll('.$count.')" class="btn btn-primary">
	<input name="btnBTL" type="reset" value="BATAL" class="btn btn-warning">
	<input name="btnHPS" type="submit" value="HAPUS" class="btn btn-danger">
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
xclose($koneksi);
exit();
?>