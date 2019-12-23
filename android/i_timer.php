<?php
session_start();

require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");
	




$filenyax = "$sumber/android/i_timer.php";




//PROSES ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//jika batas waktu pengerjaan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'sisawaktu'))
	{
	//ambil nilai
	$skd = trim(cegah($_GET['skd']));
	$jkd = trim(cegah($_GET['jkd']));
	$tablenya = "siswaujian$skd";
	$tablenilai = "siswanilai$skd";


	//detail jkd jadwal
	$qku2 = mysql_query("SELECT * FROM m_jadwal ".
							"WHERE kd = '$jkd'");
	$rku2 = mysql_fetch_assoc($qku2);
	mysql_free_result($qku2);
	$u_durasi = balikin($rku2['durasi']);


	//ketahui waktu mulai
	$qku = mysql_query("SELECT round(DATE_FORMAT(waktu_mulai, '%H')) AS jamku, ".
							"round(DATE_FORMAT(waktu_mulai, '%i')) AS menitku, ".
							"round(DATE_FORMAT(waktu_mulai, '%s')) AS detikku, ".
							"$tablenilai.* ".
							"FROM $tablenilai ".
							"WHERE jadwal_kd = '$jkd' ".
							"ORDER BY postdate ASC");
	$rku = mysql_fetch_assoc($qku);
	mysql_free_result($qku);
	$ku_kd = nosql($rku['kd']);
	$ku_jamku = nosql($rku['jamku']);
	$ku_menitku = nosql($rku['menitku']);
	$ku_detikku = nosql($rku['detikku']);	

	
	
	//tanggal saat ini...	
	$tahun = date("Y");
	$bulan2 = date("M");
	$tanggal = date("d");		
	$asal = "$ku_jamku:$ku_menitku:$ku_detikku";
	
	
	
	//jadikan menit
	$u_durasi2 = trim($u_durasi - 1); //90menit
	 
	$tujuan = date('H:i:s',strtotime($asal . '+'.$u_durasi2.' minutes'));
	
	//pecah
	$tujuanku = explode(":", $tujuan);
	$tujuan_jam = trim($tujuanku[0]);
	$tujuan_menit = trim($tujuanku[1]);
	$tujuan_detik = $ku_detikku;




	//update waktu akhir
	$waktu_akhir = "$tahun-$bulan-$tanggal $tujuan_jam:$tujuan_menit:$tujuan_detik";
	
	mysql_query("UPDATE $tablenilai SET waktu_akhir = '$waktu_akhir' ".
					"WHERE kd = '$ku_kd'");






	//tujuan akhir selesai
	$qku = mysql_query("SELECT round(DATE_FORMAT(waktu_akhir, '%H')) AS jamku, ".
							"round(DATE_FORMAT(waktu_akhir, '%i')) AS menitku, ".
							"round(DATE_FORMAT(waktu_akhir, '%s')) AS detikku, ".
							"$tablenilai.* ".
							"FROM $tablenilai ".
							"WHERE jadwal_kd = '$jkd' ".
							"ORDER BY postdate DESC");
	$rku = mysql_fetch_assoc($qku);
	mysql_free_result($qku);
	$ku_kd = nosql($rku['kd']);
	$ku_jamku = nosql($rku['jamku']);
	$ku_menitku = nosql($rku['menitku']);
	$ku_detikku = nosql($rku['detikku']);	







	//jalankan timer...
	//echo "$ku_jamku. $ku_menitku. $ku_detikku";
	//echo "$bulan2";
	?>
	<p id="demo1"></p>
	
	<script>
	// Set the date we're counting down to
	var countDownDate = new Date("<?php echo $bulan2;?> <?php echo $tanggal;?>, <?php echo $tahun;?> <?php echo $ku_jamku;?>:<?php echo $ku_menitku;?>:<?php echo $ku_detikku;?>").getTime();
	
	// Update the count down every 1 second
	var x = setInterval(function() {
	
	  // Get today's date and time
	  var now = new Date().getTime();
	    
	  // Find the distance between now and the count down date
	  var distance = countDownDate - now;
	    
	  // Time calculations for days, hours, minutes and seconds
	  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	    
	  // Output the result in an element with id="demo"
	  //document.getElementById("demo").innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
	  //document.getElementById("demo1").innerHTML = minutes + "menit, " + seconds + "detik ";
	  document.getElementById("demo1").innerHTML = hours + ":" + minutes + ":" + seconds + "";

	    
	  // If the count down is over, write some text 
	  if (distance < 0) {
	    clearInterval(x);
	    document.getElementById("demo1").innerHTML = "EXPIRED";
	    
	    window.location.href = "main.html";
	  }
	}, 1000);
	</script>
		
	
	
	<?php
	//null-kan
	mysql_free_result();
	xclose($koneksi);
	exit();
	}
	

	








//jika batas waktu pengerjaan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'sisawaktu2'))
	{
	//ambil nilai
	$skd = trim(cegah($_GET['skd']));
	$jkd = trim(cegah($_GET['jkd']));
	$tablenya = "siswaujian$skd";
	$tablenilai = "siswanilai$skd";


	//detail jkd jadwal
	$qku2 = mysql_query("SELECT * FROM m_jadwal ".
							"WHERE kd = '$jkd'");
	$rku2 = mysql_fetch_assoc($qku2);
	mysql_free_result($qku2);
	$u_durasi = balikin($rku2['durasi']);


	//ketahui waktu mulai
	$qku = mysql_query("SELECT round(DATE_FORMAT(waktu_mulai, '%H')) AS jamku, ".
							"round(DATE_FORMAT(waktu_mulai, '%i')) AS menitku, ".
							"round(DATE_FORMAT(waktu_mulai, '%s')) AS detikku, ".
							"$tablenilai.* ".
							"FROM $tablenilai ".
							"WHERE jadwal_kd = '$jkd' ".
							"ORDER BY postdate ASC");
	$rku = mysql_fetch_assoc($qku);
	mysql_free_result($qku);
	$ku_kd = nosql($rku['kd']);
	$ku_jamku = nosql($rku['jamku']);
	$ku_menitku = nosql($rku['menitku']);
	$ku_detikku = nosql($rku['detikku']);	

	
	
	//tanggal saat ini...	
	$tahun = date("Y");
	$bulan2 = date("M");
	$tanggal = date("d");		
	$asal = "$ku_jamku:$ku_menitku:$ku_detikku";
	
	
	
	//jadikan menit
	$u_durasi2 = trim($u_durasi - 1); //90menit
	 
	$tujuan = date('H:i:s',strtotime($asal . '+'.$u_durasi2.' minutes'));
	
	//pecah
	$tujuanku = explode(":", $tujuan);
	$tujuan_jam = trim($tujuanku[0]);
	$tujuan_menit = trim($tujuanku[1]);
	$tujuan_detik = $ku_detikku;




	//update waktu akhir
	$waktu_akhir = "$tahun-$bulan-$tanggal $tujuan_jam:$tujuan_menit:$tujuan_detik";
	
	mysql_query("UPDATE $tablenilai SET waktu_akhir = '$waktu_akhir' ".
					"WHERE kd = '$ku_kd'");






	//tujuan akhir selesai
	$qku = mysql_query("SELECT round(DATE_FORMAT(waktu_akhir, '%H')) AS jamku, ".
							"round(DATE_FORMAT(waktu_akhir, '%i')) AS menitku, ".
							"round(DATE_FORMAT(waktu_akhir, '%s')) AS detikku, ".
							"$tablenilai.* ".
							"FROM $tablenilai ".
							"WHERE jadwal_kd = '$jkd' ".
							"ORDER BY postdate DESC");
	$rku = mysql_fetch_assoc($qku);
	mysql_free_result($qku);
	$ku_kd = nosql($rku['kd']);
	$ku_jamku = nosql($rku['jamku']);
	$ku_menitku = nosql($rku['menitku']);
	$ku_detikku = nosql($rku['detikku']);	







	//jalankan timer...
	//echo "$ku_jamku. $ku_menitku. $ku_detikku";
	//echo "$bulan2";
	?>
	<p id="demo2"></p>
	
	<script>
	// Set the date we're counting down to
	var countDownDate = new Date("<?php echo $bulan2;?> <?php echo $tanggal;?>, <?php echo $tahun;?> <?php echo $ku_jamku;?>:<?php echo $ku_menitku;?>:<?php echo $ku_detikku;?>").getTime();
	
	// Update the count down every 1 second
	var x = setInterval(function() {
	
	  // Get today's date and time
	  var now = new Date().getTime();
	    
	  // Find the distance between now and the count down date
	  var distance = countDownDate - now;
	    
	  // Time calculations for days, hours, minutes and seconds
	  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
	  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
	    
	  // Output the result in an element with id="demo"
	  document.getElementById("demo2").innerHTML = hours + ":" + minutes + ":" + seconds + "";

	    
	  // If the count down is over, write some text 
	  if (distance < 0) {
	    clearInterval(x);
	    document.getElementById("demo1").innerHTML = "EXPIRED";
	    
	    window.location.href = "main.html";
	  }
	}, 1000);
	</script>
		
	
	
	<?php
	//null-kan
	mysql_free_result();
	xclose($koneksi);
	exit();
	}
	

	










//set postdate proses kerjakan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'setpostdate'))
	{
	//ambil nilai
	$skd = trim(cegah($_GET['skd']));
	$jkd = trim(cegah($_GET['jkd']));
	$tablenya = "siswaujian$skd";
	$tablenilai = "siswanilai$skd";


	//update
	mysql_query("UPDATE $tablenilai SET waktu_proses = '$today' ".
					"WHERE jadwal_kd = '$jkd'");

	  
	//null-kan
	mysql_free_result();
	xclose($koneksi);
	exit();
	}
	
?>