<?php
session_start();


//ambil nilai
require("inc/config.php");
require("inc/fungsi.php");
require("inc/koneksi.php");
$tpl = LoadTpl("template/login_siswa.html");



nocache;

//nilai
$filenya = "ujian.php";
$filenya_ke = $sumber;
$judul = "LOGIN SISWA";
$judulku = $judul;






//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if ($_POST['btnOK'])
	{
	//ambil nilai
	$username = cegah($_POST["usernamex"]);
	$password = md5(cegah($_POST["passwordx"]));

	//cek null
	if ((empty($username)) OR (empty($password)))
		{
		//re-direct
		$pesan = "Input Tidak Lengkap. Harap Diulangi...!!";
		pekem($pesan,$filenya);
		exit();
		}
	else
		{
		//query
		$q = mysql_query("SELECT * FROM siswa ".
							"WHERE usernamex = '$username' ".
							"AND passwordx = '$password' LIMIT 0,1");
		$row = mysql_fetch_assoc($q);
		$total = mysql_num_rows($q);
	
		//cek login
		if (!empty($total))
			{
			session_start();
	
			
			//jika admin
			$kdku = nosql($row['kd']);
			$kunis = balikin($row['nis']);
			$kunama = balikin($row['nama']);
			
			//bikin session
			$_SESSION['kd61_session'] = nosql($row['kd']);
			$_SESSION['username61_session'] = $username;
			$_SESSION['pass61_session'] = $password;
			$_SESSION['siswa_session'] = "$kunis. $kunama";
			$_SESSION['hajirobe_session'] = $hajirobe;
	
	
			//re-direct
			$ke = "ujian/index.php";
			xloc($ke);
			exit();
			}
		else
			{
			//re-direct
			$pesan = "PASSWORD SALAH... SILAHKAN ULANGI LAGI...";
			pekem($pesan, $filenya);
			exit();
			}
		}
	
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////








//isi *START
ob_start();



//view //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<form action="'.$filenya.'" method="post" name="formx">

<p>
Username :
<br>
<input name="usernamex" type="text" size="15" class="btn btn-warning btn-block">
</p>


<p>
Password :
<br>
<input name="passwordx" type="password" size="15" class="btn btn-warning btn-block">
</p>


<p>
<input name="btnOK" type="submit" value="KIRIM &gt;&gt;&gt;" class="btn btn-danger">
</p>


</form>';
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//isi
$isi = ob_get_contents();
ob_end_clean();

require("inc/niltpl.php");


//diskonek
xclose($koneksi);
exit();
?>
