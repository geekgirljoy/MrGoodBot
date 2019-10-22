<?php

// Example Use (Set skin & generate a face):
/*
	http://localhost/MrGoodBot/Face.php?skin=Default
	http://localhost/MrGoodBot/Face.php?skin=Nucleus
	http://localhost/MrGoodBot/Face.php?skin=Pixel
	http://localhost/MrGoodBot/Face.php?skin=Pumpkin
*/

// $_GET || $_POST skin
if(isset($_REQUEST['skin'])){
	$skin = $_REQUEST['skin'];
}
else{
    $skin = 'Default';
}

$path = __DIR__ . DIRECTORY_SEPARATOR . 'skins' . DIRECTORY_SEPARATOR . $skin . DIRECTORY_SEPARATOR;

// Find The Skin Template Images
$eyes = glob($path . "eyes_*.png");
$noses = glob($path . "nose_*.png");
$mouths = glob($path . "mouth_*.png");
$eyebrows = glob($path . "eyebrows_*.png");


// Select A Random Face
$eye = array_rand($eyes, 1);
$eyebrow = array_rand($eyebrows, 1);
$nose = array_rand($noses, 1);
$mouth = array_rand($mouths, 1);
$face = $path . 'face.png';


// Load Selected Part Images
$face = imagecreatefrompng($face); // Load Face
$eyes = imagecreatefrompng($eyes[$eye]);  // Load Eyes
$eyebrow = imagecreatefrompng($eyebrows[$eyebrow]); // Load Eyebrows
$nose = imagecreatefrompng($noses[$nose]);  // Load Nose
$mouth = imagecreatefrompng($mouths[$mouth]); // Load Mouth


// Enable Transparency 
imagealphablending($face, true);
imagesavealpha($face, true);


// What size is the image?
$width = imagesx($face);
$height = imagesy($face);


// Merge Parts of face image resources together
imagecopy($face, $eyes,0, 0, 0, 0, $width, $height);
imagecopy($face, $eyebrow,0, 0, 0, 0, $width, $height);
imagecopy($face, $nose,0, 0, 0, 0, $width, $height);
imagecopy($face, $mouth,0, 0, 0, 0, $width, $height);


// Save New Face Image in the Root Skins DIR
imagepng($face, __DIR__ . DIRECTORY_SEPARATOR . 'skins' . DIRECTORY_SEPARATOR .'current_face.png');


#FreeTheMemory!!!
imagedestroy($face);
imagedestroy($eyes);
imagedestroy($eyebrow);
imagedestroy($nose);
imagedestroy($mouth);
