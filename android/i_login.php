<?php
session_start();


//ambil nilai
require("../inc/config.php");
require("../inc/fungsi.php");
require("../inc/koneksi.php");



$filenyax = "$sumber/android/i_login.php";






//form
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'form'))
	{
	?>

	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
	
		$("#btnKRM").on('click', function(){
			$("#formx2").submit(function(){
				$.ajax({
					url: "<?php echo $filenyax;?>?aksi=simpan",
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
	echo '<table width="100%" border="0" cellpadding="5" cellspacing="5">
	<tr align="center">
	
	<td width="10">&nbsp;</td>
	<td valign="top">
	
	<div id="ihasil"></div>
	
	<form name="formx2" id="formx2">
	<p>
	Username : 
	<br>
	<input name="iuser" id="iuser" value="" type="text" class="btn btn-block btn-success">
	</p>
	
	<p>
	Password :
	<br>
	<input name="ipass" id="ipass" value="" type="password" class="btn btn-block btn-success">
	</p>
	
	<p>
	<input type="submit" name="btnKRM" id="btnKRM" value="KIRIM >" class="btn btn-block btn-danger">
	</p>
	
	
	</form>
	
	</td>
	
	<td width="10">&nbsp;</td>
	</tr>
	</table>

	<br>
	<br>
	<br>';
	
	//null-kan
	mysql_free_result();
	xclose($koneksi);
	exit();
	}













//jika simpan
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'simpan'))
	{
	//ambil nilai
	$euser = cegah($_GET['iuser']);
	$epass = md5(cegah($_GET['ipass']));

	
	//empty
	if ((empty($euser)) OR (empty($epass)))
		{
		echo '<b>
		<font color="red">GAGAL. SILAHKAN ULANGI LAGI...!!</font>
		</b>';
		
		//null-kan
		mysql_free_result();
		xclose($koneksi);
		exit();	
		} 
	else
		{
		//cek
		$qku = mysql_query("SELECT * FROM siswa ".
								"WHERE usernamex = '$euser' ".
								"AND passwordx = '$epass'");
		$rku = mysql_fetch_assoc($qku);
		$tku = mysql_num_rows($qku);
		
		//jika null
		if (empty($tku))
			{
			echo '<b>
			<font color="red">
			LOGIN GAGAL. <br>SILAHKAN ULANGI LAGI...!!
			</font>
			</b>';
			//null-kan
			mysql_free_result();
			xclose($koneksi);
			exit();
			}
		else
			{
			//lanjut
			$ku_kd = nosql($rku['kd']);
			$ku_nama = balikin($rku['nama']);
			$ku_passx = balikin($rku['passwordx']);
			
			//bikin sesi
			$_SESSION['sesiku'] = $ku_kd;
			$_SESSION['sesinama'] = $ku_nama;
			$_SESSION['passx'] = $ku_passx;
	
			//null-kan
			mysql_free_result();
			xclose($koneksi);
			?>
			
			
			
			<script language='javascript'>
			//membuat document jquery
			$(document).ready(function(){
					window.location.href = "main.html"; 
			
			});
			
			</script>
			
			<?php
			
	
			}
								
								
		}	

	
	exit();
	}













//jika logout
if ((isset($_GET['aksi']) && $_GET['aksi'] == 'logout'))
	{
	//habisi
	session_unset();
	session_destroy();
	
	//null-kan
	mysql_free_result();
	xclose($koneksi);
	?>
	
	
	
	<script language='javascript'>
	//membuat document jquery
	$(document).ready(function(){
			window.location.href = "main.html"; 
	
	});
	
	</script>
	
	<?php
	
	exit();
	}






exit();
?>
