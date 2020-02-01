<html>
<body>
<table border = '1' align="center" width="75%">

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



	echo '<form action ="simpan_apl02.php" method="post" enctype="multipart/form-data">';
	$skema = $_POST["skema"];
	if(!empty($skema)){
		$result = "SELECT KODESKEMA from skema where KODESKEMA = '$skema'";
		$hasil = mysqli_query($link,$result);
		if(mysqli_num_rows($hasil)>0){
			while($row = mysqli_fetch_assoc($hasil)){
				$skema = $row['KODESKEMA'];
				echo '<input type = "hidden" name = "skema" value= "'.$skema.'">';
			}
		}
	}
	$cariskema = mysqli_query($link,"SELECT NOMORSKEMA,NAMASKEMA from skema where KODESKEMA ='$skema'");
	if(mysqli_num_rows($cariskema)>0){
		while($row = mysqli_fetch_assoc($cariskema)){
			echo '<tr>';
			echo '<th>Nomor Skema</th>';
			echo '<th colspan = "5">' . $row["NOMORSKEMA"] . '</th></tr>';
			echo '<tr>';
			echo "<th>Nama Skema</th>";
			echo '<th colspan = "5">' . $row["NAMASKEMA"] . "</th></tr>";
		}
	}

	$uk = "SELECT * from unitkompetensi uk inner join detailskema ds where ds.IDSKEMA = '$skema' and ds.IDUK = uk.IDUK";
	$searchuk = mysqli_query($link,$uk);
	$number = '1'; $unit='1';
	if(mysqli_num_rows($searchuk)>0){
		while($baris = mysqli_fetch_assoc($searchuk)){
			echo '<tr>';
			echo '<th>Kode Unit</th>';
			echo '<th colspan = "5">' . $baris["KODEUNIT"] . '</th></tr>';
			echo '<input type = "hidden" name = "kodeunit['.$unit.']" value= "'.$baris['KODEUNIT'].'">';
			echo '<tr>';
			echo "<th>Nama Unit</th>";
			echo '<th colspan = "5">' . $baris["NAMAUNIT"] . "</th></tr>";
			$uk = $baris['IDUK'];
			echo '<tr>';
			echo '<th colspan = "2" >Pertanyaan</th>';
			echo '<th>Kompeten</th>';
			echo '<th>Belum Kompeten</th>';
			echo '<th>Bukti - bukti Pendukung</th>';
			echo '</tr>';

			$soal = "SELECT * from soal where KODEUNIT = '$uk'";
			$searchsoal = mysqli_query($link,$soal);
			$nomor = '1';
			if(mysqli_num_rows($searchsoal)>0){
				while($row = mysqli_fetch_assoc($searchsoal)){
					echo '<tr>';
					echo '<th>' . $nomor . '</th>';
					echo '<th>' . $row["SOAL"] . '</th>';
					echo '<input type = "hidden" name = "kodesoal['.$number.']" value= "'.$row['KODESOAL'].'">';
					echo '<th><input type = "radio" name = "radio['.$number.']" value = "1" required></th>';
					echo '<th><input type = "radio" name = "radio['.$number.']" value = "0" required></th>';
					echo '<th><input type = "file" name = "file['.$number.']" ></th>';
					$number++;$nomor++;

				}
			}
			$unit++;
			echo "<tr></tr>";
		}
		echo '<button type = "submit" name = "submit" value ="submit" formaction = "simpan_apl02.php">submit</button>';
		echo '<input type = "checkbox" name = "persetujuan" value="1" required > Dengan mencentang checkbox ini, maka dianggap data diatas sudah sesuai dengan bukti fisik yang ada';
	}

	
	echo '</form>';
?>
<form method="post" action="apl02.php">
<input type="submit" value="Kembali">
</form>
</table>
</body>
</html>