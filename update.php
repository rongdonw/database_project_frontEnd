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

				// Define the query.
		$q = "SELECT COUNT(*) AS NUM_BUDGETS_FOR_FORM FROM FUNDS F WHERE FORM_NUMBER = {$form_number}";
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		oci_fetch($s))
		$num_budgets = oci_result($s, 'NUM_BUDGETS_FOR_FORM');
		$cost_per_budget = round(floatval($amount)/$num_budgets, 2);

		// Define the query.
		$q = "SELECT BID FROM FUNDS WHERE FORM_NUMBER = {$form_number}";
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		while(oci_fetch($s)){
			$bid = oci_result($s, 'BID');
			// Define the query.
			$q2 = "UPDATE BUDGET SET REMAINING_AMOUNT = REMAINING_AMOUNT - {$cost_per_budget} WHERE BID = {$bid}";
			// Parse the query.
			$s2 = oci_parse($c, $q2);
			// Execute the query.
			oci_execute($s2);
		}

	}

	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo $out;
}
?>

