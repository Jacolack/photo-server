
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

    
    	<div id="body">
	<!-- Body start -->

<?php
$GETLocation = clean_input($_GET["location"]);
function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>


    <div class="createDiv">
	<center>
        <p class="orLbl">Your folder has been created.</p>
	<br>
	<a class="createPicker" href="/index.php?location=<?php echo $GETLocation;?>">View in Enclosing Folder</a>
	<br>
	<br>
	<a class="createPicker" href="/createFolder.php">Create Another Folder</a>
	<br>
	<br>
	<a class="createPicker" href="/upload.php">Upload Image</a>
	</center>
    
	</div>  


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
