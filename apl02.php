<html>
<body>
	<table border=1 align="center">
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

	$leveluser = "SELECT LEVEL from user where USERNAME = $username";

	if($result = mysqli_query($link,$leveluser)){
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$leveluser = $row['LEVEL'];
			}
		}
	}


	if($leveluser = '1'){
		echo '<form action ="isiapl02.php" method="post">';
		$skema = "SELECT KODESKEMA from uji u inner join peserta p, user us where p.IDUJIKOMPETENSI = u.IDUJI and p.NIK = us.USERNAME and us.USERNAME = '$username'";
		$skemauser = mysqli_query($link,$skema);
		if(mysqli_num_rows($skemauser)>0 ){
			while($row = mysqli_fetch_assoc($skemauser)){
				echo '<tr>';
				echo '<th><input type = "submit" value = "'.$row['KODESKEMA'].'" name = "skema" ></th>';
				$skema = $row['KODESKEMA'];
				$namaskema = "SELECT NAMASKEMA from skema where KODESKEMA = '$skema'";
				$hasil = mysqli_query($link,$namaskema);
				if(mysqli_num_rows($hasil)>0){
					while($nama = mysqli_fetch_assoc($hasil)){
						echo '<th>'.$nama['NAMASKEMA'].'</th>';
						echo '<br>';
						echo '</tr>';
					}
				}
			} 
		} 
		echo '</form>';
		
	}
	
	if ($leveluser = '2'){
		
		$cariasesi = " SELECT * from peserta p inner join asesi a, uji u, skema s where p.NIPASESOR = '$username' and p.NIK = a.NIK and p.IDUJIKOMPETENSI=u.IDUJI and u.KODESKEMA = s.KODESKEMA ";
		$query = mysqli_query($link,$cariasesi);
		if(mysqli_num_rows($query)>0){
			while($row = mysqli_fetch_assoc($query)){
				echo '<form action ="asesi.php" method="post">';
				echo '<tr>';
				echo '<th><input type = "submit" value = "'.$row['IDPESERTA'].'" name = "asesi" ></th>';
				echo '<th>'.$row['NIK'].'</th>';
				echo '<th>'.$row['NAMA'].'</th>';
				echo '<th>'.$row['NAMASKEMA'].'</th>';
				echo '<input type = "hidden" value = "'.$row['KODESKEMA'].'" name = "skemauser" >';
				echo '</tr>';
				echo '</form>';
			}
		}
		
	}	
?>
<form method="post" action="apl02.php">
<input type="submit" value="Kembali"></form>
</table>
</body>
</html>