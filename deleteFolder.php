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
	$folderID = (int) clean_input($_GET["id"]);
	$returnTo = 0;

	$res = mysqli_query($conn, "SELECT * FROM folders WHERE parent = '". $folderID . "'");
	$resThis = mysqli_query($conn, "SELECT * FROM folders WHERE id = '". $folderID . "'");
	$photosRes = mysqli_query($conn, "SELECT * FROM photos WHERE parent = '". $folderID . "'");
	
	if (mysqli_num_rows($resThis)==0) {
		echo "Error: folder does not exist";
	} else {	
		while($row = mysqli_fetch_assoc($resThis)) {
			$returnTo = $row["parent"];
		}	
		if (mysqli_num_rows($res)!=0 || mysqli_num_rows($photosRes)!=0) {
			echo "Error: folder is not empty";
		} else {
			$deleteQ = mysqli_query($conn, "DELETE FROM folders WHERE id = '". $folderID . "'");
			header("Location:/index.php?location=".$returnTo);
			exit();
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
