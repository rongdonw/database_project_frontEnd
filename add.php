<?php if (isset($_POST['type'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');

	$out = "";	

	if ($_POST['type'] == 'Residence_Hall') {
		// Define the query.
		$q = "INSERT INTO {$_POST['type']} VALUES ('{$_POST['address']}', '{$_POST['hall_name']}', {$_POST['num_residents']}, {$_POST['num_lounges']}, {$_POST['CA_sid_dropdown']})";
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
	} elseif ($_POST['type'] == 'RA' || $_POST['type'] == 'CA') {
		// Define the query.
		$q = "SELECT MAX(SID) AS MAX FROM STAFF_MEMBER";
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		oci_fetch($s);
		$new_sid = oci_result($s, 'MAX') + 1;

		$q = "INSERT INTO Staff_Member VALUES ($new_sid, 
			'{$_POST['name']}',
			{$_POST['room_num']},
			{$_POST['phone_num']},
			'{$_POST['hall_name']}')";
		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);

		if ($_POST['type'] == 'RA') {
			$q = "INSERT INTO Residence_Advisor VALUES ($new_sid, 
			'{$_POST['floors_managed']}',
			{$_POST['num_residents']},
			{$_POST['CA_sid_dropdown']})";
			// Parse the query.
			$s = oci_parse($c, $q);
			// Execute the query.
			oci_execute($s);
		} else if ($_POST['type'] == 'CA') {
			$q = "INSERT INTO Community_Advisor VALUES ($new_sid, 
			{$_POST['p_card_num']})";
			// Parse the query.
			$s = oci_parse($c, $q);
			// Execute the query.
			oci_execute($s);
		}
	} elseif ($_POST['type'] == 'Budget') {
		$q = "SELECT MAX(BID) AS MAX FROM BUDGET";
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		oci_fetch($s);
		$new_bid = oci_result($s, 'MAX') + 1;

		$q = "INSERT INTO Budget VALUES ($new_bid, 
			'{$_POST['name']}',
			{$_POST['sid_dropdown']},
			{$_POST['starting_amount']},
			{$_POST['starting_amount']})";
		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);
	} elseif ($_POST['type'] == 'Program') {
		$q = "SELECT MAX(PID) AS MAX FROM PROGRAM";
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		oci_fetch($s);
		$new_pid = oci_result($s, 'MAX') + 1;

		$q = "INSERT INTO PROGRAM VALUES ($new_pid, 
			to_date('{$_POST['date']}', 'yyyy-mm-dd'),
			'{$_POST['time']}',
			'{$_POST['location']}',
			'{$_POST['event_name']}',
			{$_POST['funds_requested']})";
		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);

		$organizers  = $_POST['sid'];
		$organizers_arr = explode(",", $organizers);
		
		foreach ($organizers_arr as &$organizer) {
    		if ($organizer != "") {
			$q = "INSERT INTO ORGANIZES VALUES ({$organizer}, {$new_pid})";
			// Parse the query.
			$s = oci_parse($c, $q);

			// Execute the query.
			oci_execute($s);	
			}
		}
	} elseif ($_POST['type'] == 'Expense') {
		$form_number = $_POST['form_number'];
		$q = "INSERT INTO EXPENSE_FORM(FORM_NUMBER, MAX_AMOUNT) VALUES ($form_number, 
			{$_POST['max_amount']})";
		// Parse the query.
		$s = oci_parse($c, $q);

		// Execute the query.
		oci_execute($s);

		$users  = $_POST['sid'];
		$users_arr = explode(",", $users);
		
		foreach ($users_arr as &$user) {
    		if ($user != "") {
			$q = "INSERT INTO USES VALUES ({$user}, {$form_number})";
			// Parse the query.
			$s = oci_parse($c, $q);

			// Execute the query.
			oci_execute($s);	
			}
		}

		$budgets  = $_POST['bid'];
		$budgets_arr = explode(",", $budgets);
		
		foreach ($budgets_arr as &$budget) {
    		if ($budget != "") {
			$q = "INSERT INTO FUNDS VALUES ({$budget}, {$form_number})";
			// Parse the query.
			$s = oci_parse($c, $q);

			// Execute the query.
			oci_execute($s);	
			}
		}

		if(isset($_POST['pid'])){
			$programs  = $_POST['pid'];
			$programs_arr = explode(",", $programs);
			
			foreach ($programs_arr as &$program) {
	    		if ($program != "") {
				$q = "INSERT INTO PAYSFOR VALUES ({$form_number}, {$program})";
				// Parse the query.
				$s = oci_parse($c, $q);

				// Execute the query.
				oci_execute($s);	
				}
			}
		}
	}

	// Close the connection.
	oci_close($c);
}
?>
