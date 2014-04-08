<?php 
// No HTML required by this script!
// Validate that the page received $_GET['email']:
if (isset($_GET['type'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');
	
	// Define the query.
	$q = "SELECT hall_name FROM residence_hall";

	// Parse the query.
	$s = oci_parse($c, $q);
	
	// Initialize the PHP variable:
	//$rows = array();

	// Bind the output to $rows:
	//oci_define_by_name($s, "halls", $rows);

	// Execute the query.
	oci_execute($s);
	
	$out = "";

	// Fetch the results.
	while(oci_fetch($s)) {
		$hall = trim(oci_result($s, 'HALL_NAME'));
		$newoption = "<option value='" . $hall . "'>" . $hall . "</option>";
		$out .= $newoption;	
	}

	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo $out;
}
?>
