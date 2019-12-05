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

<audio id="Admin-Test-Audio"><source src="AdminTest.wav" type="audio/wav">Your browser doesn't support the audio element.</audio>

<input type="checkbox" name="toggleIncomplete" onclick='ToggleViewStatments("incomplete")' <?php if($_REQUEST['ShowIncomplete'] === '1'){echo 'checked ';} ?>>Show Incomplete 
&nbsp;
<input type="checkbox" name="toggleComplete" onclick='ToggleViewStatments("complete")' <?php if($_REQUEST['ShowComplete'] === '1'){echo 'checked ';} ?>>Show Complete<br> 


<h3>Add New Statement</h3>

<?php
// To preserve show parameters during post as $_GET params
$post_action = $_SERVER['PHP_SELF'];
$post_action .= '?ShowIncomplete=' . $_REQUEST['ShowIncomplete'];
$post_action .= '&ShowComplete=' . $_REQUEST['ShowComplete'];
?>

<form method="post" action="<?php echo $post_action; ?>">
   <input type="text" name="statement">
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
