<html>
<body>
<?php
	$link = mysqli_connect("localhost","root","","lsp-baru");
	if($link===false){
		die("Could not connect".mysqli_connect_error());
	}
	echo "Connected Successfully"; echo '<br>';

	session_start();
	if(!isset($_SESSION['USERNAME'])) {
	   header('location:login.php'); 
	} else { 
	//mengambil value username
	   $username = $_SESSION['USERNAME'];
	}

	$iduser = $_POST['idpeserta'];
	
	
	$codesoal = count($_POST['kodesoal']);

	for ($i=1; $i <= $codesoal; $i++) {
		$jawaban = $_POST['jawaban'];
		if($jawaban[$i]=='1'){

			$v = $_POST['cb1'];
			$a = $_POST['cb2'];
			$t = $_POST['cb3'];
			$m = $_POST['cb4'];

			$kodesoal = $_POST['kodesoal'];
			$update = " UPDATE jawaban set V = '$v[$i]', A = '$a[$i]', T = '$t[$i]', M = '$m[$i]' WHERE KODESOAL = '$kodesoal[$i]' and IDPESERTA = '$iduser' ";
			mysqli_query($link,$update) or trigger_error(mysqli_error($link));
			
		}
	}
	if ($update) {
		echo 'update berhasil';
	}


?>
<form method="post" action="apl02.php">
<input type="submit" value="Kembali">
</form>
</body>
</html>
