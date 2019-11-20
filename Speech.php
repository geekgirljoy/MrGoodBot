<?php



/*
  _____           _   _ _ _       _   _                      
 |_   _|         | | (_) | |     | | (_)                     
   | |  _ __  ___| |_ _| | | __ _| |_ _  ___  _ __           
   | | | '_ \/ __| __| | | |/ _` | __| |/ _ \| '_ \          
  _| |_| | | \__ \ |_| | | | (_| | |_| | (_) | | | |         
 |_____|_| |_|___/\__|_|_|_|\__,_|\__|_|\___/|_| |_|         
 |_   _|         | |                 | | (_)                 
   | |  _ __  ___| |_ _ __ _   _  ___| |_ _  ___  _ __  ___  
   | | | '_ \/ __| __| '__| | | |/ __| __| |/ _ \| '_ \/ __| 
  _| |_| | | \__ \ |_| |  | |_| | (__| |_| | (_) | | | \__ \ 
 |_____|_| |_|___/\__|_|   \__,_|\___|\__|_|\___/|_| |_|___/ 
                                                             
*/


/*

 \ \        / /_   _| \ | |       
  \ \  /\  / /  | | |  \| |       
   \ \/  \/ /   | | | . ` |       
    \  /\  /   _| |_| |\  |       
     \/  \/   |_____|_| \_|                                  
*/
// SAPI is already installed by default.
// Use this function to List available SAPI voices
// 0 = Microsoft David Desktop - English (United States)
// 1 = Microsoft Zira Desktop - English (United States)
// ... If you have additional voices installed
function ListSAPIVoices(&$voice){
    foreach($voice->GetVoices as $v){
        echo $v->GetDescription . PHP_EOL;
    }
}
                                 


/*
  __  __          _____ 
 |  \/  |   /\   / ____|
 | \  / |  /  \ | |     
 | |\/| | / /\ \| |     
 | |  | |/ ____ \ |____ 
 |_|  |_/_/    \_\_____|
*/
// Mac has it's own Speech Synthesis system
// accessible via the "say" command.
// To use eSpeak on a Mac, change this variable to true.
$mac_use_espeak = false;

// To use eSpeak on a Mac you need to install
// Homebrew Package Manager & eSpeak 
// Run these commands in a terminal:
/* 

/usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"

brew install espeak

*/


/*
  _      _____ _   _ _    ___   __
 | |    |_   _| \ | | |  | \ \ / /
 | |      | | |  \| | |  | |\ V / 
 | |      | | | . ` | |  | | > <  
 | |____ _| |_| |\  | |__| |/ . \ 
 |______|_____|_| \_|\____//_/ \_\                                  
*/
// Install eSpeak - Run this command in a terminal
/*

 sudo apt-get install eSpeak
 
*/




/////////////////////////////////////////////////////
/*
  _____           _____                      _     
 |  __ \         / ____|                    | |    
 | |  | | ___   | (___  _ __   ___  ___  ___| |__  
 | |  | |/ _ \   \___ \| '_ \ / _ \/ _ \/ __| '_ \ 
 | |__| | (_) |  ____) | |_) |  __/  __/ (__| | | |
 |_____/ \___/  |_____/| .__/ \___|\___|\___|_| |_|
                       | |                         
                       |_|                         
*/


// Ask PHP what OS it was compiled for, 
// CAPITALIZE it and truncate to the first 3 chars.
$OS = strtoupper(substr(PHP_OS, 0, 3));



// Is this MR. Good Bot or Admin?
// $_GET || $_POST admin
if(isset($_REQUEST['admin'])){
    $filename = __DIR__ . DIRECTORY_SEPARATOR . "AdminTest.wav";
}
else{
    // Note: Mac say may require 4 char .wave extension
    //       if you receive an error about format try
    //       changing this to MrGoodBot.wave
    $filename = __DIR__ . DIRECTORY_SEPARATOR . "MrGoodBot.wav";
}


// What should it say?
// $_GET || $_POST statement
if(isset($_REQUEST['statement'])){
    $statement = $_REQUEST['statement'];
}
else{ // Try to get statement from database
    $conn = ConnectToMySQL();
    $Incomplete_Sentences = LoadInCompleteSentences($conn);
    @$statement = current($Incomplete_Sentences);
    @$statement = $statement['Statement'];
}

// If there is something to say
if(strlen($statement) > 1 ){

    // What OS is the Server?
    if($OS === 'WIN'){
        // WIN32, WINNT, Windows
        
        // COM (Component Object Model) objects
        // https://www.php.net/manual/en/book.com.php
        $voice = new COM("SAPI.SpVoice");
        $file_stream = new COM("SAPI.SpFileStream");

        // Change $voice to David
        $voice->Voice = $voice->GetVoices()->Item(0);
        
        /*
        Select Stream Quality:
        11kHz 8Bit Mono = 8
        11kHz 8Bit Stereo = 9
        11kHz 16Bit Mono = 10
        11kHz 16Bit Stereo = 11
        ...
        16kHz 8Bit Mono = 16
        16kHz 8Bit Stereo = 17
        16kHz 16Bit Mono = 18;
        16kHz 16Bit Stereo = 19
        ...
        32kHz 8Bit Mono = 28
        32kHz 8Bit Stereo = 29
        32kHz 16Bit Mono = 30
        32kHz 16Bit Stereo = 31
        ...
        */
        $file_stream->Format->Type = 11; // 11kHz 16Bit Stereo
        
        /*
        Select Speech StreamFile Mode:
        Read = 0
        ReadWrite = 1
        Create = 2
        CreateForWrite = 3
        */
        $mode = 3; // CreateForWrite
        
        // Output TTS to File
        $file_stream->Open($filename, $mode); // Open stream and create file    
        $voice->AudioOutputStream = $file_stream; // Begin streaming TTS

        // Have $voice speak $statement
        $voice->Speak($statement); 
        $file_stream->Close; // Close stream

    } 
    elseif($OS === 'LIN' || $OS === 'DAR'){
        $voice = "espeak";
        $save_file_args = "-w $filename"; // eSpeak args

        // If this is Darwin (MacOS) AND we don't want eSpeak
        if($OS === 'DAR' && $mac_use_espeak == false) { 
            $voice = "say -v 'Alex'";
            $save_file_args = "-o $filename"; // say args
        }

        // Output statement to .wav file.
        exec("$voice '$statement' $save_file_args");
        
        // If this is Darwin (MacOS) and the file type is WAVE
        // rename .wave to .wav
        // note this expects the audio is in the same folder
        // as this script and will not preserve a path
        if($OS === 'DAR' && strtoupper(pathinfo($filename, PATHINFO_EXTENSION)) == 'WAVE' ){
            rename(basename($filename, '.wave') . '.wav', $filename);
        }
    }
    $Something_To_Say = '1'; // Let Mr. Good Bot JS know to play statement
}
else{
    $Something_To_Say = '0'; // No statement
}
?>
