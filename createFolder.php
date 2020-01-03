<?php
// define variables and set to empty values

session_start();
if (!$_SESSION["verified"]) {
	header("Location: /login.php");
}

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$folderID = clean_input($_POST["parent"]);
	$folderName = clean_input($_POST["folderName"]);
	$successful = 1;

	if (!preg_match("/^[a-zA-Z0-9_]*$/", $folderName)) {
	    echo "Incorrect folder name formatting\n";
	    $successful = 0;
	}

	if ($successful == 1) {
		$res = mysqli_query($conn, "SELECT * FROM folders WHERE id = '" . $folderID . "'");
		if (mysqli_num_rows($res)==0) {
	    		echo "Location does not exist.\n";
	    		$successful = 0;
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
