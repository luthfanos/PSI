<html>
<body>
	<table border =1 align="center">
<?php
	$link = mysqli_connect("localhost","root","","lsp-baru");
	if($link===false){
		die("Could not connect".mysqli_connect_error());
	}
	echo "Connected Successfully"; echo '<br>';

	echo '<form action ="simpanasesor_apl02.php" method="post" >';
	$asesi = $_POST['asesi'];
	$kode = $_POST['skemauser'];

	echo '<input type = "hidden" name = "idpeserta" value = "'.$asesi.'" >';

	$num = '1';
	$uk = "SELECT * from detailskema ds where ds.IDSKEMA = '$kode' ";
	$searchuk = mysqli_query($link,$uk);
	echo '<th>SOAL</th>';
	echo '<th>KOMPETEN</th>';
	echo '<th>Bukti</th>';
	echo '<th>Valid</th>';
	echo '<th>Asli</th>';
	echo '<th>Terkini</th>';
	echo '<th>Memadai</th>';
	if(mysqli_num_rows($searchuk)>0){
		while($baris = mysqli_fetch_assoc($searchuk)){

			$kodeuk = $baris['IDUK'];
			$soal = "SELECT * from soal where KODEUNIT = '$kodeuk'";
			$searchsoal = mysqli_query($link,$soal);

			if(mysqli_num_rows($searchsoal)>0){
				while($row = mysqli_fetch_assoc($searchsoal)){
					echo '<tr>';
					echo '<th>' . $row["SOAL"] . '</th>';
					$kodesoal = $row['KODESOAL'];

					echo '<input type = "hidden" name = "kodesoal['.$num.']" value = "'.$kodesoal.'" >';
					$jawaban = "SELECT * from jawaban j where j.IDPESERTA = '$asesi' and j.KODESOAL = '$kodesoal' ";
					$query = mysqli_query($link,$jawaban);

					if(mysqli_num_rows($query)>0){
						while($row = mysqli_fetch_assoc($query)){
							if($row['KOMPETEN']==='1'){
								
								echo '<th>' . $row["KOMPETEN"] . '</th>';
								$jawab = $row['KOMPETEN'];
								echo '<input type = "hidden" name = "jawaban['.$num.']" value = "'.$jawab.'" >';
								$idjawaban = $row['IDJAWABAN'];
								$bukti = "SELECT * from berkas_bukti where UPLOADBY = '$asesi' and KODESOAL = '$kodesoal' ";
								$querybukti = mysqli_query($link,$bukti);
								if(mysqli_num_rows($querybukti)>0){
									while($row = mysqli_fetch_assoc($querybukti)){
										// echo '<th>' . $row['NAMABUKTI'] . '</th>';
										$namafile = $row['NAMABUKTI'];
										$handle = opendir(dirname(realpath(__FILE__)).'/uploads/');
										    echo '<th><img src="uploads/'.$namafile.'" border="0" width="75%" /></th>';
										
										echo '<th><input type = "hidden" name = "cb1['.$num.']" value = "0" ><input type = "checkbox" name = "cb1['.$num.']" value = "1" ></th>';
										echo '<th><input type = "hidden" name = "cb2['.$num.']" value = "0" ><input type = "checkbox" name = "cb2['.$num.']" value = "1" ></th>';
										echo '<th><input type = "hidden" name = "cb3['.$num.']" value = "0" ><input type = "checkbox" name = "cb3['.$num.']" value = "1" ></th>';
										echo '<th><input type = "hidden" name = "cb4['.$num.']" value = "0" ><input type = "checkbox" name = "cb4['.$num.']" value = "1" ></th>';
									}
									$num++;
								}
							} else {
								echo '<th>' . $row["KOMPETEN"] . '</th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
								echo '<th></th>';
							}
						}
					}
					echo '</tr>';
				}
			}
		}
	}
	echo '<button type = "submit" name = "submit" value ="submit" formaction = "simpanasesor_apl02.php">submit</button>';
	echo '</form>';


	?>
</table>
<form method="post" action="apl02.php">
<input type="submit" value="Kembali">
</form>
</body>
</html>