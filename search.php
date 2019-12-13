<?php
session_start();
if ($_SESSION["verified"]) {
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search</title>
    <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" /> 
        <link rel="stylesheet" href="/main.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <div id="searchContainer">

<div id="body">
<!-- Body start -->
<ul class="nav">
  <li class="navli"><a href="/">Home</a></li>
  <li class="navli"><a class="active" href="/search.php">Search</a></li>
</ul>    



<?php
} else {
	  header("Location: /login.php?continue=" . $_SERVER["SCRIPT_NAME"]);
}
$searchTag = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$searchTag = clean_input($_POST["search"]);
}
?>

<form class="searchForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">  
        <button class="submitBtn" type="submit"><i class="fa fa-search"></i></button>
	<span class="searchSpan"><input class="searchField" type="text" placeholder="Search..." name="search" value=<?php echo $searchTag; ?>></span>
    </form> 

<?php


$servername = "localhost";
$username = "root";
$password = "cute";
$database = "images";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


echo "<div class='grid'>"; 

$res = mysqli_query($conn, "SELECT * FROM photos WHERE FIND_IN_SET('" . $searchTag . "', tags)");
while($row = mysqli_fetch_assoc($res)) {


$imageLink = "/images/" . $row['id'] . "." . $row['fileType'];
$viewLink = "/viewphoto.php?id=" . $row['id'];

echo "<div>";
echo "<a href=" . $viewLink . ">";
echo "<div style='
background: url(images/thumb" . $row['id']. "." . $row['fileType'] . ") 50% 50% no-repeat; 
background-size: cover;
height: 100%;
width: 100%;
'>";

echo "</div>";
echo "</a>";
echo "</div>";



}
echo "</div>";
}


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
			<p class="fieldExplanation"><?php echo (100 - intval(file_get_contents("percent.txt"))); ?>% storage remaining</p>
            </center>
	<!-- Footer end -->
	</div>  

        </div>
</body>
</html>
