<?php
session_start();

require("../../inc/config.php");
require("../../inc/fungsi.php");
require("../../inc/koneksi.php");
require("../../inc/cek/adm.php");
require("../../inc/class/paging.php");
$tpl = LoadTpl("../../template/admsoal.html");

nocache;

//nilai
$filenya = "soal_entri.php";
$judul = "[SOAL] Entri Soal";
$judulku = "$judul";
$judulx = $judul;
$skd = nosql($_REQUEST['skd']);
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);
$page = nosql($_REQUEST['page']);
if ((empty($page)) OR ($page == "0"))
	{
	$page = "1";
	}


$limit = 100;

//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika ke daftar
if ($_POST['btnDF'])
	{
	//re-direct
	$ke = "soal.php";
	xloc($ke);
	exit();
	}




//jika import
if ($_POST['btnIM'])
	{
	$skd = nosql($_POST['skd']);
	
	//re-direct
	$ke = "$filenya?skd=$skd&s=import";
	xloc($ke);
	exit();
	}






//import sekarang
if ($_POST['btnIMX'])
	{
	$skd = nosql($_POST['skd']);
	$filex_namex2 = strip(strtolower($_FILES['filex_xls']['name']));

	//nek null
	if (empty($filex_namex2))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		$ke = "$filenya?skd=$skd&s=import";
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
			$filex_namex2 = "soal.xls";

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
				      $i_xyz = md5("$x$i");
				      $i_no = cegah($sheet['A']);
				      $i_isi = cegah($sheet['B']);
				      $i_kunci = cegah($sheet['C']);
					  
						//cek
						$qcc = mysql_query("SELECT * FROM m_soal ".
												"WHERE jadwal_kd = '$skd' ".
												"AND no = '$i_no'");
						$rcc = mysql_fetch_assoc($qcc);
						$tcc = mysql_num_rows($qcc);
		
						//jika ada, update				
						if (!empty($tcc))
							{
							mysql_query("UPDATE m_soal SET isi = '$i_isi', ".
											"kunci = '$i_kunci', ".
											"postdate = '$today' ".
											"WHERE jadwal_kd = '$skd' ".
											"AND no = '$i_no'");
							}
		
		
						else
							{
							//insert
							mysql_query("INSERT INTO m_soal(kd, jadwal_kd, no, isi, kunci, postdate) VALUES ".
											"('$i_xyz', '$skd', '$i_no', '$i_isi', '$i_kunci', '$today')");
							}
					  
				    }
			
			    $i++;
			  }





			//hapus file, jika telah import
			$path1 = "../../filebox/excel/$filex_namex2";
			chmod($path1,0777);
			unlink ($path1);


			//re-direct
			$ke = "$filenya?skd=$skd";
			xloc($ke);
			exit();
			}
		else
			{
			//salah
			$pesan = "Bukan File .xls . Harap Diperhatikan...!!";
			$ke = "$filenya?skd=$skd&s=import";
			pekem($pesan,$ke);
			exit();
			}
		}
	}











//jika export
//export
if ($_POST['btnEX'])
	{
	$skd = nosql($_POST['skd']);
	
	
	//require
	require('../../inc/class/excel/OLEwriter.php');
	require('../../inc/class/excel/BIFFwriter.php');
	require('../../inc/class/excel/worksheet.php');
	require('../../inc/class/excel/workbook.php');




	
	//detail skd jadwal
	$qku = mysql_query("SELECT * FROM m_jadwal ".
							"WHERE kd = '$skd'");
	$rku = mysql_fetch_assoc($qku);
	$u_waktu = balikin($rku['waktu']);
	$u_pukul = balikin($rku['pukul']);
	$u_durasi = balikin($rku['durasi']);
	$u_mapel = balikin($rku['mapel']);
	$u_tingkat = balikin($rku['tingkat']);
	
	
	

	//nama file e...
	$i_filename = "soal-$u_waktu-$u_mapel-$u_tingkat.xls";
	$i_judul = "soal";
	



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
	$worksheet1->write_string(0,1,"ISI");
	$worksheet1->write_string(0,2,"KUNCI");



	//data
	$qdt = mysql_query("SELECT * FROM m_soal ".
							"WHERE jadwal_kd = '$skd' ".
							"ORDER BY round(no) ASC");
	$rdt = mysql_fetch_assoc($qdt);

	do
		{
		//nilai
		$dt_nox = $dt_nox + 1;
		$dt_no = balikin($rdt['no']);
		$dt_isi = trim(balikin($rdt['isi']));
		$dt_kunci = balikin($rdt['kunci']);



		//ciptakan
		$worksheet1->write_string($dt_nox,0,$dt_no);
		$worksheet1->write_string($dt_nox,1,$dt_isi);
		$worksheet1->write_string($dt_nox,2,$dt_kunci);
		}
	while ($rdt = mysql_fetch_assoc($qdt));


	//close
	$workbook->close();

	
	
	//re-direct
	$ke = "$filenya?skd=$skd";
	xloc($ke);
	exit();
	}








//nek batal
if ($_POST['btnBTL'])
	{
	//nilai
	$skd = nosql($_POST['skd']);

	//re-direct
	$ke = "$filenya?skd=$skd";
	xloc($ke);
	exit();
	}





//jika cari
if ($_POST['btnCARI'])
	{
	//nilai
	$skd = nosql($_POST['skd']);	
	$kunci = cegah($_POST['kunci']);


	//re-direct
	$ke = "$filenya?skd=$skd&kunci=$kunci";
	xloc($ke);
	exit();
	}




//nek entri baru
if ($_POST['btnBARU'])
	{
	//nilai
	$skd = nosql($_POST['skd']);
	
	//re-direct
	$ke = "$filenya?skd=$skd&s=baru&kd=$x";
	xloc($ke);
	exit();
	}







//jika simpan
if ($_POST['btnSMP'])
	{
	$skd = nosql($_POST['skd']);
	$s = nosql($_POST['s']);
	$kd = nosql($_POST['kd']);
	$page = nosql($_POST['page']);
	$e_no = cegah($_POST['e_no']);
	$editor = cegah2($_POST['editor']);
	$e_kunci = cegah($_POST['e_kunci']);

	//nek null
	if ((empty($e_no)) OR (empty($editor)) OR (empty($e_kunci)))
		{
		//re-direct
		$pesan = "Belum Ditulis. Harap Diulangi...!!";
		$ke = "$filenya?skd=$skd&s=$s&kd=$kd";
		pekem($pesan,$ke);
		exit();
		}
	else
		{
		//jika update
		if ($s == "edit")
			{
			mysql_query("UPDATE m_soal SET no = '$e_no', ".
							"isi = '$editor', ".
							"kunci = '$e_kunci', ".
							"postdate = '$today' ".
							"WHERE jadwal_kd = '$skd' ".
							"AND kd = '$kd'");

			//re-direct
			$ke = "$filenya?skd=$skd";
			xloc($ke);
			exit();
			}



		//jika baru
		if ($s == "baru")
			{
			//cek
			$qcc = mysql_query("SELECT * FROM m_soal ".
									"WHERE jadwal_kd = '$skd' ".
									"AND no = '$e_no'");
			$rcc = mysql_fetch_assoc($qcc);
			$tcc = mysql_num_rows($qcc);

			//nek ada
			if ($tcc != 0)
				{
				//re-direct
				$pesan = "Sudah Ada. Silahkan Ganti Yang Lain...!!";
				$ke = "$filenya?skd=$skd&s=baru&kd=$kd";
				pekem($pesan,$ke);
				exit();
				}
			else
				{
				mysql_query("INSERT INTO m_soal(kd, jadwal_kd, no, isi, kunci, postdate) VALUES ".
								"('$kd', '$skd', '$e_no', '$editor', '$e_kunci', '$today')");

				//re-direct
				$ke = "$filenya?skd=$skd";
				xloc($ke);
				exit();
				}
			}
		}
	}




//jika hapus
if ($_POST['btnHPS'])
	{
	//ambil nilai
	$skd = nosql($_POST['skd']);
	$jml = nosql($_POST['jml']);
	$page = nosql($_POST['page']);
	$ke = "$filenya?skd=$skd&page=$page";

	//ambil semua
	for ($i=1; $i<=$jml;$i++)
		{
		//ambil nilai
		$yuk = "item";
		$yuhu = "$yuk$i";
		$kd = nosql($_POST["$yuhu"]);

		//del
		mysql_query("DELETE FROM m_soal ".
						"WHERE jadwal_kd = '$skd' ".
						"AND kd = '$kd'");
		}

	//auto-kembali
	$ke = "$filenya?skd=$skd";
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



<script type="text/javascript" src="<?php echo $sumber;?>/inc/class/ckeditor/ckeditor.js"></script>


  
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

	$qx = mysql_query("SELECT * FROM m_soal ".
						"WHERE jadwal_kd = '$skd' ".
						"AND kd = '$kdx'");
	$rowx = mysql_fetch_assoc($qx);
	$e_no = balikin($rowx['no']);
	$editor = balikin($rowx['isi']);
	$e_kunci = balikin($rowx['kunci']);

	?>
	
	
	
	<div class="row">

	<div class="col-md-6">
		
	<?php
	echo '<form action="'.$filenya.'" method="post" name="formx2">
	
	
	<p>
	Nomor Urut : 
	<br>
	<input name="e_no" type="text" value="'.$e_no.'" size="5" class="btn btn-warning">
	</p>
	
	
	
	<p>
	Isi Soal, Lengkap dengan Opsi Pilihan Ganda : 
	<br>
	<textarea id="editor" name="editor" rows="20" cols="80" style="width: 100%" class="btn-warning">'.$editor.'</textarea>
	
	</p>
	
	
	<p>
	Kunci Jawaban : 
	<br>
	<select name="e_kunci" class="btn btn-warning">
	<option value="'.$e_kunci.'" selected>'.$e_kunci.'</option>
	<option value="A">A</option>
	<option value="B">B</option>
	<option value="C">C</option>
	<option value="D">D</option>
	<option value="E">E</option>
	</select>
	</p>
	
	
	<p>
	<input name="jml" type="hidden" value="'.$count.'">
	<input name="s" type="hidden" value="'.$s.'">
	<input name="kd" type="hidden" value="'.$kdx.'">
	<input name="page" type="hidden" value="'.$page.'">
	<input name="skd" type="hidden" value="'.$skd.'">
	
	<input name="btnSMP" type="submit" value="SIMPAN" class="btn btn-danger">
	<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
	</p>
	
	
	</form>';
	
	?>
		
	


	</div>
	
	</div>



	
		
	<script type="text/javascript">
	//<![CDATA[
	var roxyFileman = '<?php echo $sumber;?>/inc/class/ckeditor/plugins/fileman/index.html';
	 
	$(function(){
    CKEDITOR.replace( 'editor',{filebrowserBrowseUrl:roxyFileman,
                         filebrowserImageBrowseUrl:roxyFileman+'?type=image',
                         removeDialogTabs: 'link:upload;image:upload'}); 
	});


	//]]>
	</script>
	

	<?php
	}
	






/*


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
		<input name="skd" type="hidden" value="'.$skd.'">
		<input name="btnBTL" type="submit" value="BATAL" class="btn btn-info">
		<input name="btnIMX" type="submit" value="IMPORT >>" class="btn btn-danger">
	</p>
	
	
	</form>';	
	?>
		


	</div>
	
	</div>


	<?php
	}

*/













else
	{
	//jika null
	if (empty($kunci))
		{
		$sqlcount = "SELECT * FROM m_soal ".
						"WHERE jadwal_kd = '$skd' ".
						"ORDER BY round(no) ASC";
		}
		
	else
		{
		$sqlcount = "SELECT * FROM m_soal ".
						"WHERE jadwal_kd = '$skd' ".
						"AND isi LIKE '%$kunci%' ".
						"ORDER BY round(no) ASC";
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
	
	
	
	
	//detail skd jadwal
	$qku = mysql_query("SELECT * FROM m_jadwal ".
							"WHERE kd = '$skd'");
	$rku = mysql_fetch_assoc($qku);
	$u_waktu = balikin($rku['waktu']);
	$u_pukul = balikin($rku['pukul']);
	$u_durasi = balikin($rku['durasi']);
	$u_mapel = balikin($rku['mapel']);
	$u_tingkat = balikin($rku['tingkat']);
	
	
	
	
	//ketahui jumlah soal
	$qmboh = mysql_query("SELECT * FROM m_soal ".
							"WHERE jadwal_kd = '$skd'");
	$tmboh = mysql_num_rows($qmboh);
	
	//update
	mysql_query("UPDATE m_jadwal SET soal_postdate = '$today', ".
					"soal_jml = '$tmboh' ".
					"WHERE kd = '$skd'");
	
	
	
	echo '<form action="'.$filenya.'" method="post" name="formxx">
	
	<p>
	[<b>'.$u_waktu.'</b>]. [<b>'.$u_pukul.'</b>]. [<b>'.$u_durasi.' Menit</b>].
	</p>
	
	<p>
	Mapel : <b>'.$u_mapel.'</b>, Kelas : <b>'.$u_tingkat.'</b>
	</p>
	
	
	<p>
	<input name="skd" type="hidden" value="'.$skd.'">
	<input name="btnBARU" type="submit" value="ENTRI BARU" class="btn btn-danger">
	<input name="btnDF" type="submit" value="LIHAT MAPEL LAIN >" class="btn btn-danger">
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
	<td><strong><font color="'.$warnatext.'">ISI</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">KUNCI</font></strong></td>
	<td width="50"><strong><font color="'.$warnatext.'">POSTDATE</font></strong></td>
	
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
			$i_isi = balikin($data['isi']);
			$i_kunci = balikin($data['kunci']);
			$i_postdate = balikin($data['postdate']);

			
			echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
			echo '<td>
			<input type="checkbox" name="item'.$nomer.'" value="'.$i_kd.'">
	        </td>
			<td>
			<a href="'.$filenya.'?skd='.$skd.'&s=edit&page='.$page.'&kd='.$i_kd.'"><img src="'.$sumber.'/template/img/edit.gif" width="16" height="16" border="0"></a>
			</td>
			<td>'.$i_no.'</td>
			<td>'.$i_isi.'</td>
			<td>'.$i_kunci.'</td>
			<td>'.$i_postdate.'</td>
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
	<input name="skd" type="hidden" value="'.$skd.'">

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