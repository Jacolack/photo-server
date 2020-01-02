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
<div id="container">
		<!-- Body start -->
		<div id="body">
			<ul class="nav">
				<li class="navli"><a class="active" href="/">Home</a></li>
				<li class="navli"><a href="/search.php">Search</a></li>
				<li class="rightNavBtn"><button id="folderBtn"><img src = "/assets/newFolder.png"></button></li>
				<li class="rightNavBtn"><button id="uploadBtn"><img src = "/assets/upload.png"></button></li>
			</ul>
			<div class='grid'>
<?php
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
$folderID = 0;
$folderName = "";
$locErr = "";
$nameErr = "";

// GET real location
$GETLocation = clean_input($_GET["location"]);

if ($GETLocation != "") {
	$folderID = (int)$GETLocation;
}

$res = mysqli_query($conn, "SELECT * FROM folders WHERE parent = '". $folderID . "'");
while($row = mysqli_fetch_assoc($res)) {
	$link = "/index.php?location=" . $row['id'];
	echo "			<div draggable='true' class='gridBoxFolder' id='folder" . $row['id'] . "' data-id='" . $row['id'] . "'>";
	echo "				<a class='gridBoxLink' href=" . $link . ">";
	echo "					<div class='folderOuter'>";
	echo "						<div class='cls-context-menu-folder' data-id='" . $row['id'] . "' data-largest='y'>";
	echo "							<img draggable='false' class='cls-context-menu-folder' data-id='" . $row['id'] . "' src='/assets/folder.svg'>";
	echo "							<p class='cls-context-menu-folder' data-id='" . $row['id'] . "'>" . $row['name']. "</p>";
	echo "						</div>";
	echo "					</div>";
	echo "				</a>";
	echo "			</div>";
}

$res = mysqli_query($conn, "SELECT * FROM photos WHERE parent = '". $folderID . "'");
while($row = mysqli_fetch_assoc($res)) {
	$thisId = $row['id'];
	$viewLink = "/images/" . $thisId . "." . $row['fileType'];
	echo "			<div draggable='true' class='gridBoxImage' data-id='" . $row['id'] . "'>";
	echo "				<a href=" . $viewLink . ">";
	echo "					<div style='background: url(images/thumb" . $row['id']. "." . $row['fileType'] . ") 50% 50% no-repeat;'
				class='cls-context-menu-image' id='$thisId'>";
	echo "					</div>";
	echo "				</a>";
	echo "			</div>";
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
<!-- Container end -->
</div>


<!-- LEFT CLICK MENUS-->
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

<!-- New Folder Modal -->
<div id="newFolderModal" class="modal">
	<!-- Modal content -->
	<div class="modal-content">
		<div class="modal-header">
			<span id="closeFolder" class="close">&times;</span>
			<h2>Create Folder</h2>
		</div>
		<form method="post" action="createFolder.php">  
			<div class="modal-body">
				<input type="text" placeholder="Folder Name" pattern="^[a-zA-Z0-9_]*$" title="Letters, numbers, and underscores, max 32 characters." name="folderName" maxlength="32" required>
				<input type="hidden" name="parent" value=<?php echo $folderID; ?>>
			</div>
			<div class="modal-footer">
				<input type="submit" value="Create">
			</div>
		</form>
	</div>
</div>


<!-- New Image Modal -->
<div id="newImageModal" class="modal">
	<!-- Modal content -->
	<div class="modal-content">
		<div class="modal-header">
			<span id="closeUpload" class="close">&times;</span>
			<h2>Upload Image</h2>
		</div>
		<form method="post" action="upload.php" enctype="multipart/form-data">  
			<div class="modal-body">
				<input type="file" id="imageUploadInput" name="fileToUpload" required hidden>
				<input type="text" placeholder="Tags" pattern="^[a-z0-9]+(,[a-z0-9]+){4,31}$" title="Lower case letters and numbers only. Separated by comma." name="tags" minlength="9" required>
				<input type="hidden" name="parent" value=<?php echo $folderID; ?>>
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
