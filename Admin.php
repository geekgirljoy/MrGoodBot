<!DOCTYPE html>
<?php

include 'Functions.php'; // Include Mr. Good Bot functions


$conn = ConnectToMySQL();
AddSentence($conn, 'Mr. Good Bot'); // Try to add statement to database for Mr. Good Bot  
DisconnectFromMySQL($conn);

// Check for ShowIncomplete and ShowComplete, set them if they are not set
if(!isset($_REQUEST['ShowIncomplete']) || !isset($_REQUEST['ShowComplete'])){
?>
<script src="adminfunctions.js"></script> 
<script>
// On first load add ShowIncomplete & ShowComplete URL GET parameters
NewPageLoad(); // from adminfunctions.js
</script>
<?php
}
else{ //  ShowIncomplete and ShowComplete parameters are set
    $conn = ConnectToMySQL();
    
    // Load Incomplete sentences?
    if($_REQUEST['ShowIncomplete'] == '1' ){
        $Incomplete_Sentences = LoadInCompleteSentences($conn);
    }
    
    // Load Complete sentences?
    if($_REQUEST['ShowComplete'] == '1' ){
        $Complete_Sentences = LoadCompleteSentences($conn);
    }
    
    DisconnectFromMySQL($conn);
}
?>
<html lang="en">
<title>Mr Good Bot</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>

table{
    border-collapse:collapse;
    border-spacing:0;
}
td{
    font-family:Arial, sans-serif;
    font-size:14px;
    padding:10px 5px;
    border-style:solid;
    border-width:1px;
    overflow:hidden;
    word-break:normal;
    border-color:black;
    text-align:left;
    vertical-align:top;
}
th{
    font-family:Arial, sans-serif;
    font-size:14px;
    font-weight:strong;
    padding:10px 5px;
    border-style:solid;
    border-width:1px;
    overflow:hidden;
    word-break:normal;
    border-color:black;
    text-align:left;
    vertical-align:top;
}
</style>

<body>
<h1>Mr Good Bot Admin Interface</h1>
<p>Welcome to the Admin Interface for Mr Good Bot</p>

<button href="#" onclick="AdminReset()">Reset State</button><br><br>

<audio id="Admin-Test-Audio"><source src="AdminTest.wav" type="audio/wav">Your browser doesn't support the audio element.</audio>

<input type="checkbox" name="toggleIncomplete" onclick='ToggleViewStatments("incomplete")' <?php if($_REQUEST['ShowIncomplete'] === '1'){echo 'checked ';} ?>>Show Incomplete 
&nbsp;
<input type="checkbox" name="toggleComplete" onclick='ToggleViewStatments("complete")' <?php if($_REQUEST['ShowComplete'] === '1'){echo 'checked ';} ?>>Show Complete<br> 


<h3>Add New Statement</h3>
<table>
<?php
// file_get_contents load QuickSayList.txt
// Strip Ends of: 
//               " " (ASCII 32 (0x20)), an ordinary space.
//               "\t" (ASCII 9 (0x09)), a tab.
//               "\n" (ASCII 10 (0x0A)), a new line (line feed).
//               "\r" (ASCII 13 (0x0D)), a carriage return.
//               "\0" (ASCII 0 (0x00)), the NUL-byte.
//               "\x0B" (ASCII 11 (0x0B)), a vertical tab.
//
// Then fire all of your guns at once and explode into... a newline delimited array
$Quick_Say = explode(PHP_EOL, Trim(file_get_contents('QuickSayList.txt')));

// Like a true nature's child, We were born to... Filter for empty values and reindex arrays
$Quick_Say = array_values(array_filter($Quick_Say));

// Before taking the world in a love embrace... find out how many elements we have left?
$quick_length = count($Quick_Say);

// Now, Head out on the highway...
$table = '';
for($i = 0; $i < ($quick_length); $i+= 3){
    $table .= '    <tr>' . PHP_EOL;
    if(!empty($Quick_Say[$i])){ // Should only be empty if QuickSayList.txt is empty and Racin' with the wind 
        $table .= '        <td> <button onclick="AddToStatement(\'' . @$Quick_Say[$i] . '\')">' . @$Quick_Say[$i] . '</td>' . PHP_EOL;
    }
    if(!empty($Quick_Say[$i+1])){
        $table .= '        <td> <button onclick="AddToStatement(\'' . @$Quick_Say[$i+1] . '\')">' . @$Quick_Say[$i+1] . '</td>' . PHP_EOL;
    }
    if(!empty($Quick_Say[$i+2])){
        $table .= '        <td> <button onclick="AddToStatement(\'' . @$Quick_Say[$i+2] . '\')">' . @$Quick_Say[$i+2] . '</button></td>' . PHP_EOL;
    }
    $table .= '    </tr>' . PHP_EOL;
}
echo $table;
?>
</table>


<?php
// To preserve show parameters during post as $_GET params
$post_action = $_SERVER['PHP_SELF'];
$post_action .= '?ShowIncomplete=' . $_REQUEST['ShowIncomplete'];
$post_action .= '&ShowComplete=' . $_REQUEST['ShowComplete'];
?>
<form method="post" action="<?php echo $post_action; ?>">
   <textarea id="statement" name="statement"></textarea>
   <input type="submit" name="submit" value="Add"><br>
</form>


<?php
// If there are Incomplete Sentences
if(!empty($Incomplete_Sentences)){
?>
<div id='incomplete'>
    <h3>Incomplete</h3>
    <table>
        <tr>
        <th nowrap>ID</th>
        <th nowrap>Status</th>
        <th nowrap>Audio Test</th>
        <th nowrap>Statement</th>
    </tr>
<?php
    $table = '';
    foreach($Incomplete_Sentences as $key=>$sentence){
        $table .= '    <tr>' . PHP_EOL;
        $table .= '        <td>' . $key . '</td>' . PHP_EOL;
        $table .= '        <td>Incomplete</td>' . PHP_EOL;
        $table .= '        <td><button href="#" onclick="AdminTestAudio(\''.$sentence['Statement'].'\')">Say It</button></td>' . PHP_EOL;
        $table .= '        <td>' . $sentence['Statement'] . '</td>' . PHP_EOL;
        $table .= '    </tr>';
    }
    $table .= '    </table>' . PHP_EOL;
    $table .= '</div>' . PHP_EOL;
    echo $table;
}
else{
    if($_REQUEST['ShowIncomplete'] === '1'){
?>
<h3>Incomplete</h3>
There are no incomplete statements.
<?php
    }
}
?>


<?php
// If there are Complete Sentences
if(!empty($Complete_Sentences)){
?>
<div id='complete'>
    <h3>Complete</h3>
    <table>
    <tr>
        <th nowrap>ID</th>
        <th nowrap>Status</th>
        <th nowrap>Audio Test</th>
        <th nowrap>Statement</th>
    </tr>
<?php
    $table = '';
    foreach($Complete_Sentences as $key=>$sentence){
        $table .= '    <tr>' . PHP_EOL;
        $table .= '        <td>' . $key . '</td>' . PHP_EOL;
        $table .= '        <td>Complete</td>' . PHP_EOL;
        $table .= '        <td><button href="#" onclick="AdminTestAudio(\''.$sentence['Statement'].'\')">Say It</button></td>' . PHP_EOL;
        $table .= '        <td>' . $sentence['Statement'] . '</td>' . PHP_EOL;
        $table .= '    </tr>' . PHP_EOL;
    }
    $table .= '    </table>' . PHP_EOL;
    $table .= '</div>' . PHP_EOL;
    echo $table;

}
else{
    if($_REQUEST['ShowComplete'] === '1'){
?>
        <h3>Complete</h3>
        There are no complete statements.
<?php
    }
}
?>
<script src="adminfunctions.js"></script> 
</body>
</html>
