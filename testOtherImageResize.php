<?php





resample("images/8.jpg", "images/thumbtest8.jpg", 200);

function resample($jpgFile, $thumbFile, $width) {
    // Get new dimensions
    list($width_orig, $height_orig) = getimagesize($jpgFile);
    $height = (int) (($width / $width_orig) * $height_orig);
    // Resample
    $image_p = imagecreatetruecolor($width, $height);
    $image   = imagecreatefromjpeg($jpgFile);
    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

    // Fix Orientation
    $exif = exif_read_data($jpgFile);

    if (!empty($exif['Orientation'])) {
    switch($exif['Orientation']) {
        case 3:
            $image_p = imagerotate($image_p, 180, 0);
            break;
        case 6:
            $image_p = imagerotate($image_p, -90, 0);
            break;
        case 8:
            $image_p = imagerotate($image_p, 90, 0);
            break;
    }
}

    // Output
    imagejpeg($image_p, $thumbFile, 90);

?>
