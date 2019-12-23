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
$filenya = "verifikasi.php";
$kd = nosql($_REQUEST['kd']);
$s = nosql($_REQUEST['s']);
$kunci = cegah($_REQUEST['kunci']);
$kunci2 = balikin($_REQUEST['kunci']);

$judul = "[MASTER] Siswa Verifikasi";
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








//jika reset password
if ($s == "reset")
	{
	//nilai
	$kd = nosql($_REQUEST['kd']);
	

	//detail
	$qx = mysql_query("SELECT * FROM siswa ".
						"WHERE kd = '$kd'");
	$rowx = mysql_fetch_assoc($qx);
	$e_nis = balikin($rowx['nis']);
	$e_nama = balikin($rowx['nama']);

	
	//passbaru
	$passbaru = substr($x,0,5);
	$passbarux = md5($passbaru);
	
	
	//update
	mysql_query("UPDATE siswa SET aktif = 'true', ".
					"aktif_postdate = '$today', ".
					"usernamex = '$e_nis', ".
					"passwordx = '$passbarux', ".
					"passwordx2 = '$passbaru' ".
					"WHERE kd = '$kd' ".
					"AND nis = '$e_nis'");
	
	//re-direct
	$pesan = "[$e_nis]. [$e_nama]. Password Baru : $passbaru";
	pekem($pesan,$filenya);
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
echo '<form action="'.$filenya.'" method="post" name="formxx">';
		
//jika null
if (empty($kunci))
	{
	$sqlcount = "SELECT * FROM siswa ".
					"ORDER BY aktif_postdate DESC";
	}
	
else
	{
	$sqlcount = "SELECT * FROM siswa ".
					"WHERE nis LIKE '%$kunci%' ".
					"OR nisn LIKE '%$kunci%' ".
					"OR nama LIKE '%$kunci%' ".
					"OR kelas LIKE '%$kunci%' ".
					"OR kelamin LIKE '%$kunci%' ".
					"OR lahir_tmp LIKE '%$kunci%' ".
					"OR lahir_tgl LIKE '%$kunci%' ".
					"OR postdate LIKE '%$kunci%' ".
					"ORDER BY aktif_postdate DESC";
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


//ketahui jumlahnya
$qyo = mysql_query("SELECT * FROM siswa");
$ryo = mysql_fetch_assoc($qyo);
$tyo = mysql_num_rows($qyo);


//yg belum aktif / belum verifikasi
$qyo2 = mysql_query("SELECT * FROM siswa ". 
						"WHERE aktif = 'false'");
$ryo2 = mysql_fetch_assoc($qyo2);
$tyo2 = mysql_num_rows($qyo2);


//sudah aktif
$yo_aktif = $tyo - $tyo2;
				



echo '<hr>
<p>
<input name="kunci" type="text" value="'.$kunci2.'" size="20" class="btn btn-warning">
<input name="btnCARI" type="submit" value="CARI" class="btn btn-danger">
<input name="btnBTL" type="submit" value="RESET" class="btn btn-info">
<input name="s" type="hidden" value="'.$s.'">

</p>
	


[TOTAL : <b>'.$tyo.'</b>]. 

[<a href="verifikasi_excel.php?s=belum" target="_blank">Belum Verifikasi : <b><font color=red>'.$tyo2.'</font></b></a>]. 
[<a href="verifikasi_excel.php?s=sudah" target="_blank">AKTIF : <b><font color=green>'.$yo_aktif.'</font></b></a>].

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

		$i_passx = balikin($data['passwordx']);
		$i_passx2 = balikin($data['passwordx2']);
	
		echo "<tr valign=\"top\" bgcolor=\"$warna\" onmouseover=\"this.bgColor='$warnaover';\" onmouseout=\"this.bgColor='$warna';\">";
		echo '<td>
		'.$i_aktif_postdate.'
		</td>
		<td>'.$i_nis.'</td>
		<td>'.$i_nisn.'</td>
		<td>
		'.$i_nama.'
					
		<hr>

		<a href="siswa_prt.php?ckd='.$i_kd.'" target="_blank" class="btn btn-block btn-success">PRINT KARTU UJIAN >></a>


		<a href="'.$filenya.'?s=reset&kd='.$i_kd.'" class="btn btn-block btn-primary">RESET PASSWORD >></a>
	
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




//isi
$isi = ob_get_contents();
ob_end_clean();

require("../../inc/niltpl.php");


//null-kan
xclose($koneksi);
exit();
?>