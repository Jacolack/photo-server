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

	$folderID = clean_input($_POST["parent"]);
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


	if ($successful == 1) {
		$res = mysqli_query($conn, "SELECT * FROM folders WHERE id = '" . $folderID . "'");
		if (mysqli_num_rows($res)==0) {
	    		$locErr = "Location does not exist.";
	    		$successful = 0;
			break;
		} else {
			$fakeLocation = $fakeLocation."/".$value;
		}
	}

	if ($successful == 1) {
		$sql = "INSERT INTO folders (name, parent) VALUES ('" . $folderName . "', '" . $location . "')";
		if (mysqli_query($conn, $sql)) {
			header("Location:/index.php?location=".$folderID);
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
<!DOCTYPE html>
<html>
<head>
    <title>Error</title>
    <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <link rel="stylesheet" href="login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<div>
	<h1 id="errorMsg">Error: Location "<?php echo $location;?>" does not exist.</h1>
    <p>Go <a href="index.php">home</a></p>
	</div>
  </body>
</html>
