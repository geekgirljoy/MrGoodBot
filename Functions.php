<?php

// DB Credentials
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'mrgoodbot';


// Create a connection MySQL
function ConnectToMySQL(){

    // Create connection
    $conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'], $GLOBALS['dbname']);
    // Check connection
    if ($conn->connect_error){
        die("MYSQL DB Connection failed: " . $conn->connect_error);
    }

    // Return connection
    return $conn;
}


// Close connection to database
function DisconnectFromMySQL(&$conn){
    $conn->close();
}


// Return the state for a bot by name as an array
function GetBotState(&$conn, $name){
    $sql = "SELECT * FROM `botstate` WHERE `Name` = '$name' LIMIT 1;";
    $result = $conn->query($sql);

    // Bot Exists
    if ($result->num_rows > 0){
        $bot_state = array();
        // Obtain the record for the pair
        while($row = $result->fetch_assoc()){
            $bot_state['ID'] = $row['ID'];    
            $bot_state['Name'] = $row['Name'];    
            $bot_state['Speaking'] = $row['Speaking'];    
            $bot_state['CustomData'] = $row['CustomData'];    
        }
        return $bot_state;
    }
    return NULL;
}


// Update the state of a bot by name
// Use GetBotState() to obtain an array you can mutate
// Make your changes then commit the state changes to the
// database using this function 
function SetBotState(&$conn, $bot){
    $sql = 'UPDATE `botstate` SET `Name` = \'' . $bot['Name'] . '\' ';
    $sql .= ', `Speaking` = ' . $bot['Speaking'];
    $sql .= ', `CustomData` = \'' . $bot['CustomData'] . '\'';
    $sql .= ' WHERE `botstate`.`ID` = ' . $bot['ID'];
    $conn->query($sql);
}


// Get an array of all Incomplete (`Status` = 0) sentences
// If a "Name" variable is $_POST/$_GET = ($_REQUEST)
// is set when this function runs it will limit query
// to only the bot specified.
function LoadInCompleteSentences(&$conn){
    
    $sql = "SELECT * FROM `statements` WHERE `Status` = 0";
    if(isset($_REQUEST['Name'])){// Name Given
        $Name = $_REQUEST['Name'];
        $sql = "SELECT * FROM `statements` WHERE `Status` = 0 AND `Bot` = '$Name'";
    }
    
    $result = $conn->query($sql);
    $statements = array();

    if (@$result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $statements[$row['ID']]['ID'] = $row['ID'];
            $statements[$row['ID']]['Statement'] = $row['Statement'];
        }
        return $statements;
    }
    return array();
}


// Get an array of all Complete (`Status` = 1) sentences
// If a "Name" variable is $_POST/$_GET = ($_REQUEST)
// is set when this function runs it will limit query
// to only the bot specified.
function LoadCompleteSentences(&$conn){
    $sql = "SELECT * FROM `statements` WHERE `Status` = 1";
    if(isset($_REQUEST['Name'])){// Name Given
        $Name = $_REQUEST['Name'];
        $sql = "SELECT * FROM `statements` WHERE `Status` = 1 AND `Bot` = '$Name'";
    }
    
    $result = $conn->query($sql);
    $statements = array();

    if (@$result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $statements[$row['ID']]['ID'] = $row['ID'];
            $statements[$row['ID']]['Statement'] = $row['Statement'];        
        }
        return $statements;
    }
    return array();
}


// If a "statement" variable is $_POST/$_GET = ($_REQUEST)
// is set when this function will add it to the statements table queue in the database
// Warning! This is insecure on a public DB
// DO NOT DEPLOY!!!! - without additional hardening against SQL Injection!
function AddSentence(&$conn, $bot){	
	// Check if posting a new Statement to Mr. Good Bot Database
    if(isset($_REQUEST['statement'])){
		// if not empty														   
		if(strlen($_REQUEST['statement']) >  0){
		    $statement = $conn->real_escape_string($_REQUEST['statement']);// prevent spellcheck
																	       // (bot added apostrophes) 
																	       // from breaking the DB
		
			$sql = "INSERT INTO `statements` (`ID`, `Bot`, `Status`, `Statement`) ";
			$sql .= "VALUES (NULL, '$bot', '0', '$statement')";
			$conn->query($sql);// Add statement
		}
	}
}


// This is used to reset the bot speech state to not speaking
// If a "Name" variable is $_POST/$_GET = ($_REQUEST)
// is set when this function runs it will limit query
// to only the bot specified. also:
// If a "Status" variable is $_POST/$_GET = ($_REQUEST)
// is set to 1 then it will also set the status of the
// current bot statement to status 1 (spoken/complete)
//
// If NO "Name" variable is $_POST/$_GET = ($_REQUEST)
// then a Full System Reset (ALL BOTS) are reset to not speaking
// Provided there are no issues the expected behavior is that
// all bots will resume speaking on their next animation cycle
// This is likely to occur with a browser load/refresh
function NotSpeaking(&$conn){
    // Set Bot State
    if(isset($_REQUEST['Name'])){ // Name Given
        // Reset Name to not speaking
        // Name will resume speaking on it's next animation cycle
        $name = $_REQUEST['Name'];        
        $sql = "UPDATE `botstate` SET `Speaking` = 0 WHERE `botstate`.`Name` = '$name'";
        
        // Update sentence status if applicable
        if(isset($_REQUEST['Status'])){// Status Given
            $status = $_REQUEST['Status'];
            if($status == 1){
                $Incomplete_Sentences = LoadInCompleteSentences($conn);
                @$statement = current($Incomplete_Sentences);
                
                if(isset($statement['ID'])){
                    $ID = $statement['ID'];
                    $conn->query("UPDATE `statements` SET `Status` = 1 WHERE `statements`.`ID` = $ID");
                }
            }
        }
    }
    else{ // No Name Given - Full System Reset (ALL BOTS)
        $sql = "UPDATE `botstate` SET `Speaking` = 0";
    }
    
    $conn->query($sql);
}

//////////////////////////////////
// Add Functions Here

