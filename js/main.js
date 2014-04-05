function createRequestObject() {
    var ro;
    if (navigator.appName == "Microsoft Internet Explorer") {
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        ro = new XMLHttpRequest();
    }
    return ro;
}
var http = createRequestObject();

// Function that calls the PHP script:
function sendRequest() {
    alert("sendRequest()");
    alert(document.getElementById('funds_requested_input').value);
    
	// Call the script.
	// Use the GET method.
	// Pass the email address in the URL.
    alert("sent");
    // example of a get request for funds_requested = 150
    http.open('get', '../ajax.php?funds_requested=' + encodeURIComponent(150));
    http.onreadystatechange = handleGetResponse;
    http.send(null);
}

// Function handles the response from the PHP script.
function handleGetResponse() {
	// If everything's okay:
    alert(http.readyState);
    alert(http.responseText);
    if(http.readyState == 4){
    	// Assign the returned value to the document object.
        document.getElementById('results').innerHTML = http.responseText;
    }
}

function addRequest() {
    alert("addRequest()");
    alert(document.getElementById('name_input').value + " " 
        + document.getElementById('location_input').value + " "  
        + document.getElementById('amount_input').value);
    http.open('put', '../ajax.php?name=' + document.getElementById('name_input').value);
    http.onreadystatechange = handlePutResponse;
    http.send(null);
}

function handlePutResponse() {
    // If everything's okay:
    alert(http.readyState);
    alert(http.responseText);
    if(http.readyState == 4){
        // Assign the returned value to the document object.
        document.getElementById('results').innerHTML = http.responseText;
    }
}

// ]]>
/*
*/
