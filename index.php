<?php
session_start();


//ambil nilai
require("inc/config.php");
require("inc/fungsi.php");
require("inc/koneksi.php");
$tpl = LoadTpl("template/cp_depan.html");



nocache;

//nilai
$filenya = "index.php";
$filenya_ke = $sumber;
$judul = "$sek_nama. Verifikasi Ujian.";
$judulku = $judul;
$mbkd = nosql($_REQUEST['mbkd']);
$s = nosql($_REQUEST['s']);
$e_nis = balikin($_REQUEST['nis']);
$e_nisn = balikin($_REQUEST['nisn']);
$e_tgl_lahir = balikin($_REQUEST['lahirtgl']);



















//isi *START
ob_start();



?>

	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){



		$("#btnKRM").on('click', function(){
			
			$("#formx2").submit(function(){
				$.ajax({
					url: "i_index.php?aksi=simpan",
					type:$(this).attr("method"),
					data:$(this).serialize(),
					success:function(data){					
						$("#ihasil").html(data);
						}
					});
				return false;
			});
		
		
		});	

	    
			
	});
	
	</script>



<?php
echo '<div class="row">
	<div class="col-12" align="center">

	

<div class="row">
	<div class="col-4">
		<br>
		
		<img src="'.$sumber.'/filebox/logo/logo2.png" alt="" width="200">
		
		<br>
		<br>
		<br>
		
		<a href="'.$sumber.'/ujian.php" class="btn btn-danger">LOGIN UJIAN</a>
	
	</div>
	<div class="col-8">


	<div class="box box-info">
	    <div class="box-header">
	      <i class="fa fa-archive"></i>
	
	      <h3 class="box-title">VERIFIKASI</h3>
	    </div>
	    <div class="box-body">

		<form name="formx2" id="formx2">

			<p>
			NIS :
			<br>
			<input name="c_nis" id="c_nis" type="text" value="'.$e_nis.'" class="btn btn-block btn-success" placeholder="NIS">
			</p>
			
			<p>
			NISN :
			<br>
			<input name="c_nisn" id="c_nisn" type="text" value="'.$e_nisn.'" class="btn btn-block btn-success" placeholder="NISN">
			</p>
			
			<p>
			Tanggal Lahir :
			<br>
			<input name="c_tgl_lahir" id="c_tgl_lahir" type="text" size="10" value="'.$e_tgl_lahir.'" class="btn btn-block btn-success" placeholder="TahunBulanTanggal">
			</p>
			
			
			<p>
			<input name="btnKRM" id="btnKRM" type="submit" class="btn btn-danger" value="KIRIM >>">
			</p>
	        </div>


			<hr>
			<div id="ihasil"></div>
		    

		    
		</form>
	
	
	</div>
	</div>

</div>




</div>

</div>';







//isi
$isi = ob_get_contents();
ob_end_clean();





















require("inc/niltpl.php");


//diskonek
xclose($koneksi);
exit();
?>
