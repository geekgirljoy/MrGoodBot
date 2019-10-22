<?php

// Example Use:
/*
    http://localhost/MrGoodBot/Fireflies.php
*/

$path = __DIR__ . DIRECTORY_SEPARATOR . 'skins' . DIRECTORY_SEPARATOR . 'Fireflies' . DIRECTORY_SEPARATOR;

// Find The Skin Template Images
$firefly_cells = glob($path . "fireflies_jar_*.png");


// Select A Random Number Of Firefly Layers Between 4 - 8
foreach(range(1, mt_rand(4, 8)) as $key){
    $fireflies[] = array_rand($firefly_cells, 1);
}


// Load Selected Image Parts
foreach($fireflies as &$cell){
    $cell = imagecreatefrompng($firefly_cells[$cell]); // Load Fireflies
}

$background = imagecreatefrompng($path . "background.png"); // Load Background
$jar = imagecreatefrompng($path . "Jar.png"); // Load Jar

// Enable Transparency 
imagealphablending($background, true);
imagesavealpha($background, true);


// What Size Is The Image?
$width = imagesx($background);
$height = imagesy($background);


// Merge Image Parts
imagecopy($background, $jar,0, 0, 0, 0, $width, $height);
foreach($fireflies as &$cell){
    imagecopy($background, $cell,0, 0, 0, 0, $width, $height);
}


// Save New Face Image In The Root Skins DIR
imagepng($background, __DIR__ . DIRECTORY_SEPARATOR . 'skins' . DIRECTORY_SEPARATOR .'current_face.png');


#FreeTheMemory!!!
foreach($fireflies as &$cell){
    imagedestroy($cell);
}
imagedestroy($jar);
imagedestroy($background);

