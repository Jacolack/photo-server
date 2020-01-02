<!DOCTYPE html>
<html>
<head>
    <title>Upload</title>
    <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" /> 
        <link rel="stylesheet" href="/main.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div id="container">
    <ul>
  <li><a href="/">Home</a></li>
  <li><a href="/search.php">Search</a></li>
  <li><a class="active" href="/create.php">Create</a></li>
</ul>    
    
    
    
<?php

ini_set('display_errors', TRUE);

$servername = "localhost";
$username = "root";
$password = "cute";
$database = "images";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}







// define variables and set to empty values
$tags = "";
$tagErr = "";
$folderID = 0;
$locErr = "";
$fileErr = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$target_dir = "images/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	echo "File Name: " . $_FILES["fileToUpload"]["name"];
	$date = new DateTime("now", new DateTimeZone('America/Los_Angeles') );
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$tags = clean_input($_POST["tags"]);
	$location = clean_input($_POST["location"]);
	echo $tags;
	echo $location;


	$uploadOk = 1;

	// Check if file already exists
	if (file_exists($real_target_file)) {
	    $fileErr = "File Exists. Please try again.";
	    $uploadOk = 0;
	}


	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 5000000000) {
	    $fileErr = "Sorry, your file is too large.";
	    $uploadOk = 0;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    $fileErr = "Sorry, only images are allowed. You uploaded a ".".".$imageFileType . " file.";
	    $uploadOk = 0;
	}

	// Ensure there is a file
	if($imageFileType == "") {
	    $fileErr = "No image.";
	    $uploadOk = 0;
	}


	// Check tags formatting
	if (!preg_match("/^[a-z]+(,[a-z]+)+$/", $tags)) {
	    $tagErr = "Incorrect formatting";
	    $uploadOk = 0;
	} else {

	    $explodedTags = explode(",", $tags);
	    // Check tags count
	    if (count($explodedTags) < 5) {
	        $tagErr = "Not enough tags";
	        $uploadOk = 0;
	    
	    // Check tags count
	    } elseif (count($explodedTags) > 32) {
	        $tagErr = "Too many tags";
	        $uploadOk = 0;

	    // Check duplicate tags
	    } elseif (count(array_unique($explodedTags)) < count($explodedTags)) {
			$tagErr = "Duplicate tags.";
	    }
	}


	// Check location formatting
	if (!preg_match("/^(home)((\/[\w]+)+)?$/", $location)) {
	    $locErr = "Incorrect formatting";
	}	





	$explodedLocation = explode("/", $location);


	$fakeExploded = $explodedLocation;
	array_shift($fakeExploded);

	$fakeLocation = "home";
	if ($uploadOk == 1) {
	foreach ($fakeExploded as $value) {
		$res = mysqli_query($conn, "SELECT * FROM folders WHERE name = '" . $value . "' AND location = '". $fakeLocation . "'");
		if (mysqli_num_rows($res)==0) {
	    		$locErr = "Location does not exist.";
			$uploadOk = 0;
			break;
		} else {
			$fakeLocation = $fakeLocation."/".$value;
		}
	}}


	// Check if $uploadOk is still 1
	if ($uploadOk == 1) {


	$stmt = mysqli_prepare($conn, "INSERT INTO photos (tags, location, fileType, uploaded) VALUES (?, ?, ?, NOW())");
	mysqli_stmt_bind_param($stmt, 'sss', $tags, $location, $imageFileType);

	if (mysqli_stmt_execute($stmt)) {
			$theId = mysqli_insert_id ($conn);
			$real_target_file = $target_dir . $theId . "." . $imageFileType;

		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $real_target_file)) {
			make_thumb("images/". $theId. ".".$imageFileType, "images/thumb".$theId.".". $imageFileType, 200, $imageFileType);
			
			header("Location:/index.php?location=".$location);
			exit();
		} else {
			$fileErr = "Sorry, there was an error uploading your file.";
		}
		} else {
			    echo "Error: " . mysqli_stmt_error ( $stmt ) . "<br>";
		}
	}
} 

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function getRand() { 
    $characters = 'abcdefghijklmnopqrstuvwxyz'; 
    $randomString = ''; 
    for ($i = 0; $i < 10; $i++) { 
	$index = rand(0, strlen($characters) - 1); 
	$randomString .= $characters[$index]; 
    } 
    return $randomString; 
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
    	<div id="body">
	<!-- Body start -->
    
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">  
    <div class="uploadDiv">
    <div class="numberCircle">1</div>
    <label class="myLabel">
    <input type="file" name="fileToUpload" />
    <span class="uploadBtn">Choose Photo</span></label>
        <div class="chooseImage-container">
            <p class="error"><?php echo $fileErr;?></p>
        </div>
    <div class="numberCircle">2</div>
    <h2 class="stepHeader">Add Tags:</h2>
                <br>
   <div class="tagTextArea-container">
    <textarea class="tagTextArea" name="tags"><?php echo $tags;?></textarea>
    <p class="fieldExplanation">Separated by comma. Only lowercase letters. Min: 5, Max: 32</p>
            <p class="error"><?php echo $tagErr;?></p>
</div>
            <div class="numberCircle">3</div>
            <h2 class="stepHeader">Choose Location:</h2>
                <br>
        <div class="locationText-container">
          <input class="locationText" type="text" name="location" value= "<?php echo $location; ?>" />
            <p class="fieldExplanation">Must start with 'home'</p>
            <p class="error"><?php echo $locErr;?></p>
        </div>
                    <div class="numberCircle">4</div>
        <input class="submitUploadBtn" type="submit" name="submit" value="Submit">  
    </div> 
               
    </div>
</form>
	
	<div id="footer" class=" w3-center">
	<!-- Footer start -->
             <center>
		     <br>
		     <p class="fieldExplanation">Made by Jack Sheridan | 2019</p>
            </center>
	<!-- Footer end -->
	</div>  
            
        </div>
</body>
</html>
