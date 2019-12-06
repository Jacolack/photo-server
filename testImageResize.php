<?php

make_thumb("images/7.jpg", "images/thumb7.jpg", 200, "jpg");




function make_thumb($src, $dest, $desired_width, $type) {

	/* read the source image */
switch ($type)
{
	case 'jpeg':
	case 'jpg':
		$source_image = imagecreatefromjpeg($src);
	break;
	case 'gif':
		$source_image = imagecreatefromgif($src);
	break;
	case 'png':
		$source_image = imagecreatefrompng($src);
	break;
	default:
		die('Invalid image type: ' . $headers);
}
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    $exif = exif_read_data($src);

    if (!empty($exif['Orientation'])) {
    switch($exif['Orientation']) {
        case 3:
            $virtual_image = imagerotate($virtual_image, 180, 0);
            break;
        case 6:
            $virtual_image = imagerotate($virtual_image, -90, 0);
            break;
        case 8:
            $virtual_image = imagerotate($virtual_image, 90, 0);
            break;
    	}
	}
	
	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}


?>
