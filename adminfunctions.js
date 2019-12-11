function AdminTestAudio(stmnt){       
   var httpRequest = new XMLHttpRequest();
   var params = 'admin=1&' + 'statement="' + stmnt +'"';
   
   httpRequest.onreadystatechange = function(){
      if(httpRequest.readyState == 4 && httpRequest.status == 200 && httpRequest.responseText == '1'){
           
         document.getElementById("Admin-Test-Audio").src = 'AdminTest.wav?' + Math.random();
         document.getElementById("Admin-Test-Audio").play();
      }
   }
   
   httpRequest.open('POST', 'Speech.php', true);
   httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   httpRequest.send(params); 
}


// Setup the URL GET variables when the page is loaded for the first time
function NewPageLoad(){       
    var url = location.href;    // Get current URL string
    url += '?ShowIncomplete=1'; // Append the get parameter ShowIncomplete
                                // Set its value to 1
    url += '&ShowComplete=1';   // Append the get parameter ShowComplete
                                // Set its value to 1
    location.href = url;        // Reload the page
                                // This code will not run again because
                                // ShowIncomplete and ShowComplete will be set
}


// Bit Shift 0 to 1 & 1 to 0
function BitShiftToggle(value){
  return 1 >> value;
}

// Toggle if statement type is shown
// Example 1:
// URL: http://localhost/MrGoodBot/Admin.php?ShowIncomplete=0&ShowComplete=0
//
// ToggleViewStatments('incomplete')
//
// New URL: http://localhost/MrGoodBot/Admin.php?ShowIncomplete=1&ShowComplete=0
//
// Example 2:
// URL: http://localhost/MrGoodBot/Admin.php?ShowIncomplete=1&ShowComplete=1
//
// ToggleViewStatments('complete')
//
// New URL: http://localhost/MrGoodBot/Admin.php?ShowIncomplete=1&ShowComplete=0
function ToggleViewStatments(type){
    // Get params from URL
    var params = new URLSearchParams(location.search);
    
    // Toggle params
    if(type == 'incomplete'){
        params.set('ShowIncomplete', BitShiftToggle(params.get('ShowIncomplete')));
    }
    else if(type == 'complete'){
        params.set('ShowComplete', BitShiftToggle(params.get('ShowComplete')));
    }
    
    // Reload page with new params
    location.href = location.pathname + '?' + params.toString();  
}

//  Use AddToStatement to append a Quick Say statement to the statement field
function AddToStatement(statement){
    var currentStatment = document.getElementById("statement").value;
    document.getElementById("statement").value = (currentStatment + ' ' + statement).trim();
}

function AdminReset(){
    var httpRequest = new XMLHttpRequest();


    httpRequest.onreadystatechange = function(){
    if(httpRequest.readyState == 4 && httpRequest.status == 200){
           
         ToggleViewStatments('');
      }
   }
    
       httpRequest.open('GET', 'NotSpeaking.php', true);
       httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
       httpRequest.send();
}
