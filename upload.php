<?php
session_start();
if (!$_SESSION["verified"]) {
	header("Location: /login.php?continue=" . $_SERVER["SCRIPT_NAME"]);
}

ini_set('display_errors', TRUE);

$servername = "localhost";
$username = "root";
include 'assets/hidden.php';
$password = $mySQLPassword;
$database = "images";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$tags = "";
$folderID = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$target_dir = "images/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	echo "File Name: " . $_FILES["fileToUpload"]["name"];
	$date = new DateTime("now", new DateTimeZone('America/Los_Angeles') );
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$tags = clean_input($_POST["tags"]);
	$folderID = (int) clean_input($_POST["parent"]);
	echo $tags;
	echo $folderID;

	$uploadOk = 1;

	// Check if file already exists
	if (file_exists($target_file)) {
	    echo "File Exists. Please try again.\n";
	    $uploadOk = 0;
	}


	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 5000000000) {
	    echo "Sorry, your file is too large.\n";
	    $uploadOk = 0;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only images are allowed. You uploaded a ".".".$imageFileType . " file.\n";
	    $uploadOk = 0;
	}

	// Ensure there is a file
	if($imageFileType == "") {
	    echo "No image.\n";
	    $uploadOk = 0;
	}

	// Check tags formatting
	if (!preg_match("/[a-z0-9]+(,[a-z0-9]+){4,31}$/", $tags)) {
	    echo "Incorrect tag formatting\n";
	    $uploadOk = 0;
	}

	if ($uploadOk == 1) {
		$res = mysqli_query($conn, "SELECT * FROM folders WHERE id = '" . $folderID . "'");
		if ((mysqli_num_rows($res) == 0) && $folderID != 0) {
	    		echo "Location does not exist.\n";
	    		$uploadOk = 0;
		}
	}


	// Check if $uploadOk is still 1
	if ($uploadOk == 1) {


	$stmt = mysqli_prepare($conn, "INSERT INTO photos (tags, parent, fileType, uploaded) VALUES (?, ?, ?, NOW())");
	mysqli_stmt_bind_param($stmt, 'sis', $tags, $folderID, $imageFileType);

	if (mysqli_stmt_execute($stmt)) {
			$theId = mysqli_insert_id ($conn);
			$real_target_file = $target_dir . $theId . "." . $imageFileType;

		
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $real_target_file)) {
			$imageName = $theId. ".".$imageFileType;
			make_thumb("images/".$imageName , "images/thumb".$theId.".". $imageFileType, 200, $imageFileType);
			header("Location:/index.php?location=".$folderID);
			exit();
		} else {
			echo "Sorry, there was an error uploading your file.\n";
		}
		} else {
			echo "Error: " . mysqli_stmt_error ( $stmt );
		}
	}
} 

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function make_thumb($src, $dest, $desired_width, $type) {

	/* read the source image */
switch ($type)
{
	case 'jpeg':
	case 'jpg':
		$source_image = imagecreatefromjpeg($src);
	break;
	case 'gif':
		$source_image = imagecreatefromgif($src);
	break;
	case 'png':
		$source_image = imagecreatefrompng($src);
	break;
	default:
		die('Invalid image type: ' . $headers);
}
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    $exif = exif_read_data($src);

    if (!empty($exif['Orientation'])) {
    switch($exif['Orientation']) {
        case 3:
            $virtual_image = imagerotate($virtual_image, 180, 0);
            break;
        case 6:
            $virtual_image = imagerotate($virtual_image, -90, 0);
            break;
        case 8:
            $virtual_image = imagerotate($virtual_image, 90, 0);
            break;
    	}
	}
	
	/* create the physical thumbnail image to its destination */
switch ($type)
{
	case 'jpeg':
	case 'jpg':
		imagejpeg($virtual_image, $dest);
	break;
	case 'gif':
		imagegif($virtual_image, $dest);
	break;
	case 'png':
		imagepng($virtual_image, $dest);
	break;
	default:
		die('Invalid image type: ' . $headers);
}
}


?>
