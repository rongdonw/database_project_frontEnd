<?php if (isset($_POST['type'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');

	$out = "";	

	echo $_POST['type'];
	if ($_POST['type'] == 'Residence_Hall') {
		// Define the query.
		$q = "INSERT INTO {$_POST['type']} VALUES ('{$_POST['hall_name']}', '{$_POST['address']}', {$_POST['num_residents']}, {$_POST['num_lounges']}, {$_POST['CA_sid_dropdown']})";
		echo " " . $q . " ";
		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);
	}

	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo "success";
}
?>
