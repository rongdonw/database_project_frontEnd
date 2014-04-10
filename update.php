<?php if (isset($_POST['type'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');

	$out = "success";	

	if ($_POST['type'] == 'Expense') {
		$form_number = $_POST['form_number'];
		$vendor = $_POST['vendor'];
		$date = $_POST['date'];
		$amount = $_POST['amount'];
		$items_purchased = $_POST['items_purchased'];

		// Define the query.
		$q = "UPDATE EXPENSE_FORM SET VENDOR = '{$vendor}', EDATE = TO_DATE('{$date}', 'yyyy-mm-dd'), AMOUNT_SPENT = {$amount}, ITEMS_PURCHASED = '{$items_purchased}' WHERE FORM_NUMBER = {$form_number}";

		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);
	}

	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo $out;
}
?>

