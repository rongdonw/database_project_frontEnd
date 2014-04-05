<?php 
// No HTML required by this script!
// Validate that the page received $_GET['email']:
if (isset($_GET['funds_requested'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');
	
	// Define the query.
	$q = "SELECT COUNT(*) AS NUM_ROWS FROM Program WHERE FUNDS_REQUESTED='{$_GET['funds_requested']}'";

	// Parse the query.
	$s = oci_parse($c, $q);
	
	// Initialize the PHP variable:
	$rows = 0;

	// Bind the output to $rows:
	oci_define_by_name($s, "NUM_ROWS", $rows);

	// Execute the query.
	oci_execute($s);
	
	// Fetch the results.
	oci_fetch($s);
	
	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	if ($rows > 0) {
		echo 'Email address has already been registered!';
	} else {
		echo 'Email address is available!';
	}
}
?>
