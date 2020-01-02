<?php
session_start();
if (!$_SESSION["verified"]) {
	header("Location: /login.php?continue=" . $_SERVER["SCRIPT_NAME"]);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Photos</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/assets/main.css">
</head>

<body>

    <div id="barlesscontainer">

<div id="body">
                <!-- Body start -->



<?php
// define variables and set to empty values



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



$id = "";
$folderName = "";
$locErr = "";
$nameErr = "";


// GET real location

$id = clean_input($_GET["id"]);

$res = mysqli_query($conn, "SELECT * FROM photos WHERE id = '". $id . "'");
while($row = mysqli_fetch_assoc($res)) {


$thumbLoc = "images/thumb" . $row['id'] . "." . $row['fileType'];
$thumbLink = "/" . $thumbLoc;
$imageLoc = "images/" . $row['id'] . "." . $row['fileType'];
$imageLink = "/" . $imageLoc;

echo "<a href=" . $imageLink . ">";
echo "<img src=" . $thumbLink . " height='300' style='height: 50%; display:block; margin:auto;' >";
echo "</a>";

$tags = $row['tags'];
$location = $row['location'];


if (isset($_POST['deleteImage'])) {
	$deleteQ = mysqli_query($conn, "DELETE FROM photos WHERE id = '". $id . "'");
	deleteImage($thumbLoc);
	deleteImage($imageLoc);
	
	header("Location:/index.php?location=".$location);
	exit();
} else if ($_POST["tags"] != "") {

	$tags = clean_input($_POST["tags"]);
	$location = clean_input($_POST["location"]);

	$uploadOk = 1;

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
	if ($uploadOk == 1) {


	mysqli_query($conn, "UPDATE photos SET tags = '". $tags . "'  WHERE id = '". $id . "'");
	mysqli_query($conn, "UPDATE photos SET location = '". $location . "'  WHERE id = '". $id . "'");
	echo "OKKKK";
			header("Location:/index.php?location=".$location);
			exit();
		
	}



}
}

function deleteImage($location) {

	if (is_file($location)) {
	   chmod($location, 0777);
	   if (unlink($location)) {
	      echo 'File deleted';
	   } else {
	      echo 'Cannot remove that file';
	   }
	} else {
	  echo 'File does not exist';
	}
}


// Do the same for all images

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


?>


<form method="post" action="editPhoto.php?id=<?php echo $id;?>" enctype="multipart/form-data">  
    <div class="uploadDiv">
    <h2 class="stepHeader">Tags:</h2>
                <br>
   <div class="tagTextArea-container">
    <textarea class="tagTextArea" name="tags"><?php echo $tags;?></textarea>
    <p class="fieldExplanation">Separated by comma. Only lowercase letters. Min: 5, Max: 32</p>
            <p class="error"><?php echo $tagErr;?></p>
</div>
            <h2 class="stepHeader">Choose Location:</h2>
                <br>
        <div class="locationText-container">
          <input class="locationText" type="text" name="location" value= "<?php echo $location; ?>" />
            <p class="fieldExplanation">Must start with 'home'</p>
            <p class="error"><?php echo $locErr;?></p>
        </div>
        <input class="submitUploadBtn" type="submit" name="submit" value="Submit">  
    </div> 
</form>


<form method="post" action="editPhoto.php?id=<?php echo $id;?>" enctype="multipart/form-data">  
    <div class="uploadDiv">
        <input class="redDeleteBtn" type="submit" name="deleteImage" value="Delete Image">  
    </div> 
</form>

        <!-- Body end -->
        </div>


	<div id="footer">
	<!-- Footer start -->
             <center>
		     <br>
		     <p class="fieldExplanation">Made by Jack Sheridan | 2019</p>
            </center>
	<!-- Footer end -->
	</div>  

        </div>
<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>

</body>
</html>


