<?php
session_start();

//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");

nocache;




//sesi
$sesikode = cegah($_REQUEST['sesikode']);
$jkd = cegah($_REQUEST['jkd']);




//jika baca soal
if ($sesikode == "baca")
	{
	//buat sesi
	$_SESSION['sesikode'] = "baca";
	$_SESSION['jkd'] = $jkd;
	
	//null-kan
	mysql_free_result();
	xclose($koneksi);
	
	
	//re-direct
	?>


	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
			window.location.href = "soal.html";

	});
	
	</script>
	<?php
	
	exit();
	}
	
	
	?>