<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">

<html>
<head>
  <meta name="viewport" content=
  "width=device-width, initial-scale=1">

  <title>Couperari</title>

<link rel="stylesheet" href="/red.css" type="text/css">
</head>

<body>
<center>
  <h1>6</h1>

	<?php
	// define variables and set to empty values
	$answerErr = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$date = new DateTime("now", new DateTimeZone('America/Los_Angeles') );
		$real_target_file = $target_dir . "selfie5_". $date->format('Y-m-d H:i:s')."_". getRand() . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		// Check if file already exists
		if (file_exists($real_target_file)) {
		    $answerErr = "File Exists. Please try again.";
		    $uploadOk = 0;
		}
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 5000000000) {
		    $answerErr = "Sorry, your file is too large.";
		    $uploadOk = 0;
		}


		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    $answerErr = "Sorry, only images are allowed. You uploaded a ".".".$imageFileType;
		    $uploadOk = 0;
		}


		// Ensure there is a file
		if($imageFileType == "") {
		    $answerErr = "No image.";
		    $uploadOk = 0;
		}


		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 1) {
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $real_target_file)) {
			header("Location:/RED/f2ghtwfv5c.html");
			exit();
		    } else {
			$answerErr = "Sorry, there was an error uploading your file.";
		    }
		}
	} 

	function getRand() { 
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	    $randomString = ''; 
	    for ($i = 0; $i < 10; $i++) { 
		$index = rand(0, strlen($characters) - 1); 
		$randomString .= $characters[$index]; 
	    } 
	    return $randomString; 
	} 
?>

  <p>Upload a selfie with a group of at least 5 strangers.</p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">  
  <input type="file" name="fileToUpload" id="fileToUpload">
  <br>
  <span class="uploadErr"> <?php echo $answerErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
</center>
</body>
</html>

