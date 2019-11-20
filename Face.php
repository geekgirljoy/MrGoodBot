<?php
include 'Functions.php';

// Example Use (Set skin & generate a face):
/*
    http://localhost/MrGoodBot/Face.php?Skin=Default
    http://localhost/MrGoodBot/Face.php?Skin=Nucleus
    http://localhost/MrGoodBot/Face.php?Skin=Pixel
    http://localhost/MrGoodBot/Face.php?Skin=Pumpkin
*/

// $_GET || $_POST Skin
if(isset($_REQUEST['Skin'])){
    $skin = $_REQUEST['Skin'];
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


// Handle bot state for speech
$conn = ConnectToMySQL();
$GoodBotState = GetBotState($conn, 'Mr. Good Bot');
$GoodBotState['HasSpeech'] = 0;
if($GoodBotState['Speaking'] == 0){
    
    include 'Speech.php';
    // Something_To_Say instantiated in Speech.php
    $GoodBotState['HasSpeech'] = $Something_To_Say;

    // Let the server know the bot will be speaking
    if($Something_To_Say == 1){
        $UpdatedBotState = $GoodBotState;
        $UpdatedBotState['Speaking'] = 1;
        SetBotState($conn, $UpdatedBotState); // update state
    }
}
DisconnectFromMySQL($conn); 

$GoodBotState = json_encode($GoodBotState); // Encode bot state as JSON
echo $GoodBotState;// echo JSON for AJAX request
