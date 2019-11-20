<?php
include 'Functions.php';
$conn = ConnectToMySQL();
NotSpeaking($conn);
DisconnectFromMySQL($conn);
?>
