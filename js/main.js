// Main js

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

//various add/search requests and their validation, uses default response handler
//please double check the request url to make sure it is correct




function validateAddResidenceRequest(){
    var hall_name = document.getElementById('hall_name').value;
    var address = document.getElementById('address').value;
    var num_residents = document.getElementById('num_residents').value;
    var num_lounges = document.getElementById('num_lounges').value;
    
    var CA_sid_select = document.getElementById("CA_sid");
    var CA_sid = CA_sid_select.options[CA_sid_select.selectedIndex].value;
    
    if (hall_name === "" || address === "" || 
        num_residents === "" || num_lounges === ""
        || CA_sid == ""){
        return false;
    }
    return true;
}

/* Request to add Residence Hall*/
function addResidenceRequest() {
    alert("addResidenceRequest()");
    
    var CA_sid_select = document.getElementById("CA_sid");
    var CA_sid = CA_sid_select.options[CA_sid_select.selectedIndex].value;
    
    alert(document.getElementById('hall_name').value + " " 
        + document.getElementById('address').value + " "  
        + document.getElementById('num_residents').value + " "
        + document.getElementById('num_lounges').value + " "
        + CA_sid);
    
    if (validateAddResidenceRequest()){
        http.open('put', '../ajax.php?hallname=' + document.getElementById('hall_name').value
                              +  '&address=' + document.getElementById('address').value
                              +  '&numresidents=' + document.getElementById('num_residents').value
                              +  '&numlounges=' + document.getElementById('num_lounges').value
                              +  '&CAsid=' + document.getElementById('CA_sid').value);
                            
        http.onreadystatechange = handlePutResponse;
        http.send(null);
    }
    else{
        document.getElementById('results').innerHTML = "Invalid inputs supplied";   
    }
}

function validateSearchResidenceRequest(){
 var search_hall_name = document.getElementById('search_hall_name').value; 
//    if (search_hall_name === ""){
//        return false;
//    }
    return true;
}

/* Request to search Residence Hall*/
function searchResidenceRequest() {
    alert("searchResidenceRequest()");
    alert(document.getElementById('search_hall_name').value);
    
    if (validateSearchResidenceRequest()){
        http.open('get', '../ajax.php?hallname=' + document.getElementById('search_hall_name').value);                    
        http.onreadystatechange = handleGetResponse;
        http.send(null);
    }
    else{
        document.getElementById('results').innerHTML = "Invalid search inputs supplied";   
    }
}

function searchStaffRequest(){
    alert("searchStaffRequest()");
    var staffName = document.getElementById("search_staff_name").value;
    var staffHallName = document.getElementById("search_staff_hall").value;
    
    //whether user wants to search ca
    var ca_selected = document.getElementById("optionsCA").checked;
    
    alert(staffName + " " + staffHallName + " " + ca_selected);
    if (ca_selected){
     //user wants to search ca
     //which url to post?
    }
    else{
     //user wants to search ra   
     //which url to post?
    }
    
}


function addCARequest(){
    var name = document.getElementById("name").value;
    var room_num = document.getElementById("room_num").value;
    var phone_num = document.getElementById("phone_num").value;
    var hall_name = document.getElementById("hall_name").value;
    
    var p_card_num = document.getElementById("p_card_num").value;
        
        
}

function addRARequest(){
    var name = document.getElementById("name").value;
    var room_num = document.getElementById("room_num").value;
    var phone_num = document.getElementById("phone_num").value;
    var hall_name = document.getElementById("hall_name").value;
    
    var floors_managed = document.getElementById("floors_managed").value;
    var num_residents = document.getElementById("num_residents").value;
    
    var CA_sid_select = document.getElementById("CA_sid");
    var CA_sid = CA_sid_select.options[CA_sid_select.selectedIndex].value;
    
    
}

function addBudgetRequest(){
    var name = document.getElementById("name").value;
    var starting_amount = document.getElementById("starting_amount").value;
    
    var sid_select = document.getElementById("sid");
    var sid = sid_select.options[sid_select.selectedIndex].value;
    

}

function searchBudgetRequest(){
    var search_budget_name = document.getElementById("search_budget_name").value;

}


function addProgramRequest(){
    var name = document.getElementById("event_name").value;
    var location = document.getElementById("location").value;
    var date = document.getElementById("date").value;
    var time = document.getElementById("time").value;
    var funds_requested = document.getElementById("funds_requested").value;
    
    alert(date + " " + time);

}

    
function searchProgramRequest(){
    var search_event_name = document.getElementById("search_event_name").value;
    alert(search_event_name);
}

function addExpenseFormRequest(){
    var form_number = document.getElementById("form_number").value;
    var date = document.getElementById("date").value;
    var vendor = document.getElementById("vendor").value;
    var max_amount = document.getElementById("max_amount").value;
    var max_amount = document.getElementById("amount_spent").value;
    var items_purchased = document.getElementById("items_purchased").value;
}

function searchExpenseFormRequest(){
    var search_form_number = document.getElementById("search_form_number").value;
}

// Populate dropdowns ============================

function populateHallNames(){
    alert("populateHallNames()");
    
    // Call the script.
    // Use the GET method.
    // Pass the email address in the URL.
    alert("sent");
    // example of a get request for funds_requested = 150
    http.open('get', 'populate.php?type=residence_hall');
    http.onreadystatechange = handleDropdownResponse('hall_name_dropdown');
    http.send(null);
}

// Function handles the response from the PHP script.
function handleDropdownResponse(id) {
    // If everything's okay:
    alert(http.readyState);
    alert(http.responseText);
    if(http.readyState == 4){
        // Assign the returned value to the document object.
        document.getElementById(id).innerHTML = http.responseText;
    }
}

