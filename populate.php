<?php if (isset($_GET['type'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');

	$out = "";	

	if ($_GET['type'] == 'Residence_Hall') {
		// Define the query.
		$q = "SELECT {$_GET['field']} FROM {$_GET['type']}";

		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);
		

		// Fetch the results.
		while(oci_fetch($s)) {
			$hall = trim(oci_result($s, $_GET['field']));
			$newoption = "<option value='" . $hall . "'>" . $hall . "</option>";
			$out .= $newoption;	
		}
	}
	elseif ($_GET['type'] == 'Community_Advisor') {
		// Define the query.
		$q = "SELECT C.SID AS SID, NAME FROM Community_Advisor C JOIN Staff_Member S on C.SID = S.SID";

		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);
		
		$out = "";

		// Fetch the results.
		while(oci_fetch($s)) {
			$sid = trim(oci_result($s, 'SID'));
			$name = trim(oci_result($s, 'NAME'));
			$newoption = "<option value='" . $sid . "'>" . $name . "</option>";
			$out .= $newoption;	
		}
	}


	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo $out;
}
?>

