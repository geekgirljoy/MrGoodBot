<?php
// Do Stuffs Here
?>
<!DOCTYPE html>
<html lang="en">
<title>Mr Good Bot</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    background-color: #000;
    overflow: hidden;
}
.pullright{
    float:right;
    text-align:right;
} 
</style>
<body>

<select id='skin' class='pullright'>
    <option value='Default'>Default</option>
    <option value='Nucleus'>Nucleus</option>
    <option value='Pixel'>Pixel</option>
    <option value='Pumpkin'>Pumpkin</option>
</select>

<img src="" style="width:100%" id='face'>

<script>
var Face = document.getElementById('face');

function AnimateFace(){       
   var httpRequest = new XMLHttpRequest();
   var Skin = document.getElementById('skin');
   Skin = 'skin=' + Skin.options[Skin.selectedIndex].value;

   httpRequest.onreadystatechange = function(){
      if(httpRequest.readyState == 4 && httpRequest.status == 200){
         Face.src = 'skins/current_face.png?' + Math.random();
         setTimeout(AnimateFace, 120 * Math.floor(Math.random() * 11) + 1);
      }
   }
   
   httpRequest.open('POST', 'Face.php', true);
   httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   httpRequest.send(Skin); 
}
AnimateFace();


</script>

</body>
</html>
