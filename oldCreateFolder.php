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



$location = "home";
$folderName = "";
$locErr = "";
$nameErr = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$location = clean_input($_POST["location"]);
	$folderName = clean_input($_POST["folderName"]);
	$explodedLocation = explode("/", $location);
	$successful = 1;

	if (!preg_match("/^[\w]+$/", $folderName)) {
	    $nameErr = "Incorrect formatting";
	    $successful = 0;
	}

	if (!preg_match("/^(home)((\/[\w]+)+)?$/", $location)) {
	    $locErr = "Incorrect formatting";
	    $successful = 0;
	}
	    
	// Check folder count
	if (count($explodedLocation) > 31) {
	    $locErr = "Folder tree too deep. Max: 32.";
	    $successful = 0;
	}

	$fakeExploded = $explodedLocation;
	array_shift($fakeExploded);

	$fakeLocation = "home";
	if ($successful == 1) {
	foreach ($fakeExploded as $value) {
		$res = mysqli_query($conn, "SELECT * FROM folders WHERE name = '" . $value . "' AND location = '". $fakeLocation . "'");
		if (mysqli_num_rows($res)==0) {
	    		$locErr = "Location does not exist.";
	    		$successful = 0;
			break;
		} else {
			$fakeLocation = $fakeLocation."/".$value;
		}
	}}

	if ($successful == 1) {
		$sql = "INSERT INTO folders (name, location) VALUES ('" . $folderName . "', '" . $location . "')";
		if (mysqli_query($conn, $sql)) {
			header("Location:/completedFolder.php?location=".$location);
			exit();
		} else {
			    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
		}
	}
} 

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
    	<div id="body">
	<!-- Body start -->
    
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">  
    <div class="uploadDiv">
    <div class="numberCircle">1</div>

            <h2 class="stepHeader">Create Folder Name:</h2>
                <br>
        <div class="locationText-container">
          <input class="locationText" type="text" name="folderName" value= "<?php echo $folderName; ?>" />
            <p class="fieldExplanation">Letters, numbers, and underscores. Max: 32 Characters.</p>
            <p class="error"><?php echo $nameErr;?></p>
        </div>





                    <div class="numberCircle">2</div>
            <h2 class="stepHeader">Choose Location:</h2>
                <br>
        <div class="locationText-container">
          <input class="locationText" type="text" name="location" value= "<?php echo $location; ?>" />

            <p class="fieldExplanation">Must start with 'home'</p>
            <p class="error"><?php echo $locErr;?></p>
        </div>
                    <div class="numberCircle">3</div>
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
