<?php if (isset($_GET['type'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');

	$out = "";	

	if ($_GET['type'] == 'Residence_Hall') {
		// Define the query.
		$q = "SELECT * FROM {$_GET['type']} WHERE HALL_NAME = '{$_GET['hall_name']}'";
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		oci_fetch($s)

		$ca_sid = oci_result($s, $_GET['CA_sid']);

		$q2 = "SELECT NAME FROM STAFF WHERE SID = {$ca_sid}";
		// Parse the query.
		$s2 = oci_parse($c, $q2);
		// Execute the query.
		oci_execute($s2);
		oci_fetch($s2)

		$name = trim(oci_result($s, 'NAME'));
		$address = trim(oci_result($s, 'ADDRESS'));
		$num_residents = trim(oci_result($s, 'NUM_RESIDENTS'));
		$num_lounges = trim(oci_result($s, 'NUM_LOUNGES'));
		$ca_name = trim(oci_result($s2, 'NAME'));

		$out .= "<tr>";
		$out .= "<td>" . $name . "</td>";
		$out .= "<td>" . $address . "</td>";
		$out .= "<td>" . $num_residents . "</td>";
		$out .= "<td>" . $num_lounges . "</td>";
		$out .= "<td>" . $ca_name . "</td>";
	}

	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo $out;
}
?>