<!DOCTYPE html>
<!-- In case you didn't know, this is an HTML document -->
<?php
// https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
$lang = 'en'; // English

// Do other stuff here (Page/System Auth/Security?) before the page is generated/loaded

?>
<html lang="<?php echo $lang; ?>">
    <!-- Above the shoulders -->
    <head>

        <title>Mr Good Bot</title>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <style>
            body{
                background-color: #000;
                overflow: hidden;
            }
            .pullright{
             float:right;
            text-align:right;
            } 
        </style>

    </head>
    <!-- This bot is sleek and slender with all the features and the things -->
    <body>

        <!-- It puts the lotion on it's Skin selector -->
        <select id='Skin' class='pullright'>
            <option value='Default'>Default</option>
            <option value='Nucleus'>Nucleus</option>
            <option value='Pixel'>Pixel</option>
            <option value='Pumpkin'>Pumpkin</option>
        </select>

        <!-- The image of Mr. Good Bot -->
        <img src="" style="width:100%" id='MrGoodBotSpeech-Face'>

        <!-- The audio element Mr. Good Bot uses to speak -->
        <audio id="MrGoodBotSpeech-Voice">
            <source src="MrGoodBot.wav" type="audio/wav">
            Your browser doesn't support the audio element.
        </audio>

        <!-- The script that makes the puppet dance -->
        <script>
        
            // * Tested to work in Google Chrome with no changes to a fresh install    
            // * MS Edge (Internet Explorer) doesn't seem to like this and won't eat it
            // * Works with Firefox - Note: Firefox privacy permissions may by default  
            //   require you to follow these instructions to enable auto-play on the GoodBot 
            //   page - https://support.mozilla.org/en-US/kb/block-autoplay#w_site-settings
            async function Speak(){
                try{
                    await Voice.play();
                    Voice.className = "speaking";
                }
                catch(err){
                    Voice.className = "";
                }         
            }

            // This function checks with the server if new information
            // is available and updates the Mr. Good Bot Front-End as needed
            function AnimateFace(){    
               var httpRequest = new XMLHttpRequest();
               var Skin = document.getElementById('Skin');
               Skin = 'Skin=' + Skin.options[Skin.selectedIndex].value;

               // When XMLHttpRequest completed
               httpRequest.onreadystatechange = function(){
                  if(httpRequest.readyState == 4 && httpRequest.status == 200){
                      
                     //////////////
                     // Draw
                     
                     // Update Face
                     Face.src = 'skins/current_face.png?' + Math.random(); // Concatenate random numbers to prevent casheing from interfering with updates
                     
                     // Use a Timeout to continue the cycle
                     setTimeout(AnimateFace, 120 * Math.floor(Math.random() * 11) + 1);
                     
                     // / Draw
                     //////////////
                     
                     
                     //////////////
                     // Speak
                     
                     var BotState = JSON.parse(this.responseText);
                     
                     // If there is something to say and Mr. Good Bot isn't saying anything
                     if(BotState.HasSpeech == '1'
                        && BotState.Speaking == '0'
                        && Voice.className == ""){
                        // Update Audio
                        // Concatenate random numbers to prevent casheing from interfering with updates
                        Voice.src = 'MrGoodBot.wav?' + Math.random();
                        
                        // Voice Speak
                        Voice.oncanplay = function(){                         
                            Speak(); 
                        };                        
                     }
                     
                     // / Speak
                     //////////////
                  }
               }
               
               // AJAX post request, send the desired Skin
               httpRequest.open('POST', 'Face.php', true);
               httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               httpRequest.send(Skin); 
            }
            
            // This function notifies the server that Mr. Good Bot is not speaking
            // this will NOT change the state of the current statement unless
            // status == 1
            function NotSpeaking(name = '', status = '0'){
                
                // if a bot name is provided
                if(name !== ''){
                  args = 'Name=' + name + '&Status=' + status; // Create Name post field
                }
                
                // Update bot state
                var httpRequest = new XMLHttpRequest();
                httpRequest.open("POST", "NotSpeaking.php", true);
                httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                httpRequest.send(args); 
                
                httpRequest.onreadystatechange = function(){
                    if(httpRequest.readyState == 4 && httpRequest.status == 200){
                                            
                        Voice.pause();
                        Voice.className = "";
                    }
                }
            }
            
            // Variable to access/control the Face image & Voice Audio
            var Face = document.getElementById('MrGoodBotSpeech-Face');
            var Voice = document.getElementById("MrGoodBotSpeech-Voice");

            // Begin animating
            AnimateFace();
            
            // Notify server the bot just (Re)loaded and isn't currently speaking
            NotSpeaking('Mr. Good Bot'); // MAYBE: Change to ID?
                                         // ALSO: Consider security hardening this by checking credentials
                                         // ALSO: Consider adding a "default" or "public" bot that runs
                                         //       with limited functionality (if there is any) if/when
                                         //          the browser isn't logged in 

            // Listen to Mr. Good Bot to detect when the bot completes speaking
            Voice.onended = function(){ 
                NotSpeaking('Mr. Good Bot', '1'); 
            };
            
        </script>

    </body>

</html>
