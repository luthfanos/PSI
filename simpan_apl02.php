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

	$skema = $_POST['skema'];
	$idpeserta = "SELECT IDPESERTA from user u inner join peserta p, uji where u.USERNAME = $username and p.NIK = $username and p.IDUJIKOMPETENSI = uji.IDUJI and uji.KODESKEMA = $skema ";

	if($result = mysqli_query($link,$idpeserta)){
		if(mysqli_num_rows($result)>0){
			while($row = mysqli_fetch_assoc($result)){
				$idpeserta = $row['IDPESERTA'];
			}
		}
	}

	echo $idpeserta;
	echo '<br>';

	$unit = count($_POST['kodeunit']);
	$codesoal = count($_POST['kodesoal']);
	var_dump($unit);
	var_dump($codesoal);
	echo '<br>';

	$unitkom = '1';
	for ($i=1; $i <= $unit ; $i++) { 
		$kodeunit = $_POST['kodeunit'];
		echo $kodeunit[$i];
		echo '<br>';
		
		for ($j=1; $j <= $codesoal ; $j++) {
			$kodesoal = $_POST['kodesoal'];

			$uk = "SELECT KODESOAL from soal s inner join unitkompetensi u where s.KODEUNIT = u.IDUK and u.KODEUNIT = '$kodeunit[$i]'";
			$searchuk = mysqli_query($link,$uk);
			if(mysqli_num_rows($searchuk)>0){
				while($baris = mysqli_fetch_assoc($searchuk)){
					$unitkom = $baris['KODESOAL'];

					if($kodesoal[$j] == $unitkom){
						
						echo $kodesoal[$j].'  ';

						$jawaban = $_POST['radio'];
						echo $jawaban[$j];
						echo '<br>';

						$statusMsg = '';
				        // File upload path
				        $targetDir = "uploads/";
				        $fileName = basename($_FILES["file"]["name"][$j]);
				        $targetFilePath = $targetDir . $fileName;
				        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

				        if(isset($_POST["submit"]) && $jawaban[$j]!='0'){
				            // Allow certain file formats
				            $allowTypes = array('jpg','png','jpeg','gif','pdf');
				            if(in_array($fileType, $allowTypes)){
				                // Upload file to server
				                if(move_uploaded_file($_FILES["file"]["tmp_name"][$j], $targetFilePath)){
				                    // Insert image file name into database
				                    $insert = "INSERT into berkas_bukti set KODESOAL = '$kodesoal[$j]', NAMABUKTI = '$fileName', FILE = '$targetFilePath', UPLOADBY = '$idpeserta' ";
				                    mysqli_query($link,$insert) or trigger_error(mysqli_error($link));
				                    if($insert){
				                        $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
				                    }else{
				                        $statusMsg = "File upload failed, please try again.";
				                    } 
				                }else{
				                    $statusMsg = "Sorry, there was an error uploading your file.";
				                }
				            }else{
				                $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
				            }
				        }

				        // Display status message
				        echo $statusMsg;

				        if($jawaban[$j]=='1'){
				        	$idgbr = "SELECT ID from berkas_bukti where KODESOAL = '$kodesoal[$j]'";
							$searchid = mysqli_query($link,$idgbr);
							if(mysqli_num_rows($searchid)>0){
								while($row = mysqli_fetch_assoc($searchid)){
									$idgbr = $row['ID'];
									$query = "INSERT into jawaban set KODESOAL = '$kodesoal[$j]', KODEUNIT = '$kodeunit[$i]', IDPESERTA = '$idpeserta', KOMPETEN = '$jawaban[$j]', IDBUKTI = '$idgbr' ";
									mysqli_query($link,$query) or trigger_error(mysqli_error($link));
								}
							}
				        } else {
							$query = "INSERT into jawaban set KODESOAL = '$kodesoal[$j]', KODEUNIT = '$kodeunit[$i]', IDPESERTA = '$idpeserta', KOMPETEN = '$jawaban[$j]', IDBUKTI = '-' ";
							mysqli_query($link,$query) or trigger_error(mysqli_error($link));
				        }
					}
				}
			}
		}
	}
	

			
	


?>
<form method="post" action="apl02.php">
<input type="submit" value="Kembali">
</form>