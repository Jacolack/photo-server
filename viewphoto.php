<!DOCTYPE html>
<html>
<head>
    <title>Photos</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="/main.css">
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


$thumbLink = "/images/thumb" . $row['id'] . "." . $row['fileType'];
$imageLink = "/images/" . $row['id'] . "." . $row['fileType'];

echo "<a href=" . $imageLink . ">";
echo "<img src=" . $thumbLink . " height='300' style='height: 50%; display:block; margin:auto;' >";
echo "</a>";
echo "<p class='infoLbl'>Tags:" . $row['tags'] . "</p>";
echo "<p class='infoLbl'>Location:" . $row['location'] . "</p>";
echo "<a class='createPicker' target='blank' href='/editPhoto.php?id=$id'>Edit Tags/Location</a>";

echo "<br>";
echo "<br>";
$exifLink = "images/" . $row['id'] . "." . $row['fileType'];
$exif = exif_read_data($exifLink);
$lon = getGps($exif["GPSLongitude"], $exif['GPSLongitudeRef']);
$lat = getGps($exif["GPSLatitude"], $exif['GPSLatitudeRef']);

if ($lon != "") {
	echo "<a class='createPicker' target='blank' href='http://maps.google.com/maps?q=$lat,$lon'>View on the Map</a>";
}
echo "<br>";
echo "<br>";

echo "<button class='collapsible'>Open All Data</button>";
echo "<div class='content'>";



echo "<p class='infoLbl'>$imageLink:</p>";
echo "<p class='infoLbl'>Id:" . $row['id'] . "</p>";
echo "<p class='infoLbl'>FileType:" . $row['fileType'] . "</p>";

echo $exif===false ? "<p class='infoLbl'>No header data found.</p>" : "<p class='infoLbl'>Image contains headers</p>";

$exif = exif_read_data($exifLink, 0, true);
foreach ($exif as $key => $section) {
    foreach ($section as $name => $val) {
        echo "<p class='infoLbl'>$key.$name: $val</p>";
    }
}

echo "</div>";
echo "<br>";
echo "<br>";

}


function getGps($exifCoord, $hemi) {

    $degrees = count($exifCoord) > 0 ? gps2Num($exifCoord[0]) : 0;
    $minutes = count($exifCoord) > 1 ? gps2Num($exifCoord[1]) : 0;
    $seconds = count($exifCoord) > 2 ? gps2Num($exifCoord[2]) : 0;

    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);

}

function gps2Num($coordPart) {

    $parts = explode('/', $coordPart);

    if (count($parts) <= 0)
        return 0;

    if (count($parts) == 1)
        return $parts[0];

    return floatval($parts[0]) / floatval($parts[1]);
}
// Do the same for all images

function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


?>

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


