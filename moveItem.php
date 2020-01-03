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

// define variables and set to empty values
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$itemID = (int) clean_input($_GET["from"]);
	$folderID = (int) clean_input($_GET["to"]);
	$itemType = clean_input($_GET["type"]);
	$returnTo = 0;

	$res = mysqli_query($conn, "SELECT * FROM folders WHERE id = '". $folderID . "'");

	if (mysqli_num_rows($res)==0) {
		echo "Error: folder does not exist";
	} else {
		if ($itemType == "folder") {
			mysqli_query($conn, "UPDATE folders SET parent = '". $folderID . "'  WHERE id = '". $itemID . "'");
		} else {
			mysqli_query($conn, "UPDATE photos SET parent = '". $folderID . "'  WHERE id = '". $itemID . "'");
		}
		while($row = mysqli_fetch_assoc($res)) {
			$returnTo = $row["parent"];
		}	
		header("Location:/index.php?location=".$returnTo);
		exit();
	}
} 

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
