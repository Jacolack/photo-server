<!DOCTYPE html>
<html>
<head>
	<title>Move Item</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 
	<link rel="stylesheet" href="/main.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<div id="container">
<div id="body">
<!-- Body start -->
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
$folderID = 0;
$locErr = "";
$fileErr = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$itemID = (int) clean_input($_GET["from"]);
	$folderID = (int) clean_input($_GET["to"]);
	$itemType = clean_input($_GET["type"]);

	$res = mysqli_query($conn, "SELECT * FROM folderss WHERE id = '". $folderID . "'");
	if (mysqli_num_rows($res)==0) {

	} else {
		if ($itemType == "folder") {
			mysqli_query($conn, "UPDATE photos SET tags = '". $tags . "'  WHERE id = '". $id . "'");
		} else {
			mysqli_query($conn, "UPDATE photos SET tags = '". $tags . "'  WHERE id = '". $id . "'");
		}
	}
} 

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

}


?>
    
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
