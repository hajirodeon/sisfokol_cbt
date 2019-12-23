<?php
session_start();



//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");

//nilai session
$sesiku = $_SESSION['sesiku'];
$brgkd = $_SESSION['brgkd'];
$sesinama = $_SESSION['sesinama'];
$kd6_session = nosql($_SESSION['sesiku']);
$notaku = nosql($_SESSION['notaku']);
$notakux = md5($notaku);




//menu header ///////////////////////////////////////////////////////////////////////////////////////////
echo '<table border="0" width="100%">
<tr>
<td align="left" width="30">
<p class="text-primary">
	<img src="img/logo.png" height="25" />
</p>
</td>
<td align="left">
	<p class="text-primary">SMK7SMG</p>
</td>


</tr>
</table>';
?>