<?php
session_start();
if ($_SESSION["verified"]) {
?>

<!DOCTYPE html>
<html>
<head>
	<title>Photos</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="/main.css">
</head>
<body>
	<div id="container">
		<!-- Body start -->
		<div id="body">
			<ul class="nav">
				<li class="navli"><a class="active" href="/">Home</a></li>
				<li class="navli"><a href="/search.php">Search</a></li>
				<li class="navli"><a href="/create.php">Create</a></li>
				<li class="rightNavBtn"><button id="folderBtn"><img src = "/newFolder.png"></button></li>
				<li class="rightNavBtn"><button id="uploadBtn"><img src = "/upload.png"></button></li>
			</ul>
<?php
} else {
	  header("Location: /login.php?continue=" . $_SERVER["SCRIPT_NAME"]);
}
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

// GET real location
$GETLocation = clean_input($_GET["location"]);

if ($GETLocation != "") {
	$location = $GETLocation;
}

$res = mysqli_query($conn, "SELECT * FROM folders WHERE location = '". $location . "'");
echo "<div class='grid'>"; 
while($row = mysqli_fetch_assoc($res)) {
	$link = "/index.php?location=" . $row['location'] . "/" . $row['name'];
	echo "<div class='gridBoxItem'>";
	echo "<a class='gridBoxLink' href=" . $link . ">";
	echo "<div class='folderOuter'>";
	echo "<div class='cls-context-menu-folder'>";
	echo "<center class='cls-context-menu-folder'>";
	echo "<img class='cls-context-menu-folder' src='folder.svg'>";
	echo "<p class='cls-context-menu-folder'>" . $row['name']. "</p>";
	echo "</center>";
	echo "</div>";
	echo "</div>";
	echo "</a>";
	echo "</div>";
}

$res = mysqli_query($conn, "SELECT * FROM photos WHERE location = '". $location . "'");
while($row = mysqli_fetch_assoc($res)) {
	$thisId = $row['id'];
	$viewLink = "/images/" . $thisId . "." . $row['fileType'];
	echo "<div>";
	echo "<a href=" . $viewLink . ">";
	echo "<div style='
	background: url(images/thumb" . $row['id']. "." . $row['fileType'] . ") 50% 50% no-repeat; 
	background-size: cover;
	image-orientation: from-image;
	height: 100%;
	width: 100%;
	' class='cls-context-menu-image' id='$thisId'>";
	echo "</div>";
	echo "</a>";
	echo "</div>";
}

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<!-- Grid end -->
</div>
<!-- Body end -->
</div>

<!-- Footer start -->
<div id="footer">
	<br>
	<p class="fieldExplanation"><?php echo (100 - intval(file_get_contents("percent.txt"))); ?>% storage remaining</p>
<!-- Footer end -->
</div>  
</div>
<div id="div-context-menu" class="cls-context-menu">
	<ul>
		<li><a href="theLink">View Info</a></li>
	</ul>
</div>

<div id="div-context-menu-folder" class="cls-context-menu">
	<ul>
		<li><a href="theLink">Delete Folder</a></li>
	</ul>
</div>

<!-- The Modal -->
<div id="newFolderModal" class="modal">
	<!-- Modal content -->
	<div class="modal-content">
		<div class="modal-header">
			<span class="close">&times;</span>
			<h2>Create Folder</h2>
		</div>
		<form method="post" action="createFolder.php">  
			<div class="modal-body">
				<input type="text" placeholder="Folder Name" pattern="^[a-zA-Z0-9_]*$" title="Letters, numbers, and underscores, max 32 characters." name="folderName" maxlength="32" required>
				<input type="hidden" name="location" value=<?php echo $location; ?>>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Create">
			</div>
		</form>
</div>
</div>
<script src="index.js"></script>
</body>
</html>
