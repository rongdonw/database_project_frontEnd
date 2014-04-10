<?php if (isset($_GET['type'])) {
	// Connect to the database.
	// Assumes you are using PHP 5, 
	// see the PHP manual for PHP 4 examples. 
	$c = oci_connect ('lh2574', 'project', 'w4111b.cs.columbia.edu:1521/adb') OR die('Unable to connect to the database. Error: <pre>' . print_r(oci_error(),1) . '</pre>');

	$out = "";	

	if ($_GET['type'] == 'Residence_Hall') {
		// Define the query.

		if (isset($_GET['all'])) {
			$q = "SELECT * FROM RESIDENCE_HALL";
		} else {
			$q = "SELECT * FROM {$_GET['type']} WHERE HALL_NAME = '{$_GET['hall_name']}'";	
		}
		
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		while(oci_fetch($s)){	
			$ca_sid = 0;
			$ca_sid = oci_result($s, 'CA_SID');

			$q2 = "SELECT NAME FROM STAFF_MEMBER WHERE SID = {$ca_sid}";
			// Parse the query.
			$s2 = oci_parse($c, $q2);
			// Execute the query.
			oci_execute($s2);
			oci_fetch($s2);

			$name = trim(oci_result($s, 'HALL_NAME'));
			$address = trim(oci_result($s, 'ADDRESS'));
			$num_residents = trim(oci_result($s, 'NUM_RESIDENTS'));
			$num_lounges = trim(oci_result($s, 'NUM_LOUNGES'));
			$ca_name = trim(oci_result($s2, 'NAME'));

			$out .= "<tr>";
			$out .= "<td>" . $name . "</td>";
			$out .= "<td>" . $address . "</td>";
			$out .= "<td>" . $num_residents . "</td>";
			$out .= "<td>" . $num_lounges . "</td>";
			$out .= "<td>" . $ca_name . "</td></tr>";
		}
	} elseif ($_GET['type'] == 'Staff_Member') {
		// Define the query.

		$qRA = "SELECT * FROM Staff_Member S JOIN Residence_Advisor R ON S.SID = R.SID";
		$qCA = "SELECT * FROM Staff_Member S JOIN Community_Advisor C ON S.SID = C.SID";

		if (isset($_GET['all'])) {
		} elseif ($_GET['staff_type'] == 'RA') {
			$qCA = "";
			if (isset($_GET['name']) && isset($_GET['hall_name'])) {
				$qRA .= " WHERE NAME = '{$_GET['name']}' AND HALL_NAME = '{$_GET['hall_name']}'"; 
			} elseif (isset($_GET['name'])) {
				$qRA .= " WHERE NAME = '{$_GET['name']}'";
			} elseif (isset($_GET['hall_name'])) {
				$qRA .= " WHERE HALL_NAME = '{$_GET['hall_name']}'";
			}

		} elseif ($_GET['staff_type'] == 'CA') {
			$qRA = "";
			if (isset($_GET['name']) && isset($_GET['hall_name'])) {
				$qCA .= " WHERE NAME = '{$_GET['name']}' AND HALL_NAME = '{$_GET['hall_name']}'"; 
			} elseif (isset($_GET['name'])) {
				$qCA .= " WHERE NAME = '{$_GET['name']}'";
			} elseif (isset($_GET['hall_name'])) {
				$qCA .= " WHERE HALL_NAME = '{$_GET['hall_name']}'";
			}
		}

		if ($qRA != "") {
			// Parse the query.
			$s = oci_parse($c, $qRA);
			// Execute the query.
			oci_execute($s);
			while(oci_fetch($s)){	
				$ca_sid = 0;
				$ca_sid = oci_result($s, 'CA_SID');

				$q2 = "SELECT NAME FROM STAFF_MEMBER WHERE SID = {$ca_sid}";
				// Parse the query.
				$s2 = oci_parse($c, $q2);
				// Execute the query.
				oci_execute($s2);
				oci_fetch($s2);

				$name = trim(oci_result($s, 'NAME'));
				$hall_name = trim(oci_result($s, 'HALL_NAME'));
				$room_num = trim(oci_result($s, 'ROOM_NUM'));
				$phone_num = trim(oci_result($s, 'PHONE_NUM'));
				$floors_managed = trim(oci_result($s, 'FLOORS_MANAGED'));
				$num_residents = trim(oci_result($s, 'NUM_RESIDENTS'));
				$ca_name = trim(oci_result($s2, 'NAME'));

				$out .= "<tr>";
				$out .= "<td>" . $name . "</td>";
				$out .= "<td>" . $room_num . "</td>";
				$out .= "<td>" . $phone_num . "</td>";
				$out .= "<td>" . $hall_name . "</td>";
				$out .= "<td>" . "RA" . "</td>";
				$out .= "<td>" . $floors_managed . "</td>";
				$out .= "<td>" . $num_residents . "</td>";
				$out .= "<td>" . $ca_name . "</td>";
				$out .= "<td></td></tr>";


			}
		}
		if ($qCA != "") {
			// Parse the query.
			$s = oci_parse($c, $qCA);
			// Execute the query.
			oci_execute($s);
			while(oci_fetch($s)){	
				$name = trim(oci_result($s, 'NAME'));
				$hall_name = trim(oci_result($s, 'HALL_NAME'));
				$room_num = trim(oci_result($s, 'ROOM_NUM'));
				$phone_num = trim(oci_result($s, 'PHONE_NUM'));
				$p_card_num = trim(oci_result($s, 'P_CARD_NUM'));

				$out .= "<tr>";
				$out .= "<td>" . $name . "</td>";
				$out .= "<td>" . $room_num . "</td>";
				$out .= "<td>" . $phone_num . "</td>";
				$out .= "<td>" . $hall_name . "</td>";
				$out .= "<td>" . "CA" . "</td>";
				$out .= "<td></td>";
				$out .= "<td></td>";
				$out .= "<td></td>";
				$out .= "<td>" . $p_card_num . "</td></tr>";
			}
		}
		
	} elseif ($_GET['type'] == 'Budget') {
		// Define the query.
		$q = "SELECT * FROM BUDGET";
		if (isset($_GET['all'])) {
		} elseif (isset($_GET['name']) && isset($_GET['sid'])) {
			$q .= " WHERE NAME = '{$_GET['name']}' AND SID = {$_GET['sid']}"; 
		} elseif (isset($_GET['name'])) {
			$q .= " WHERE NAME = '{$_GET['name']}'";
		} elseif (isset($_GET['sid'])) {
			$q .= " WHERE SID = {$_GET['sid']}";
		}
		
		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		while(oci_fetch($s)){	
			$sid = 0;
			$sid = oci_result($s, 'SID');

			$q2 = "SELECT NAME FROM STAFF_MEMBER WHERE SID = {$sid}";
			// Parse the query.
			$s2 = oci_parse($c, $q2);
			// Execute the query.
			oci_execute($s2);
			oci_fetch($s2);

			$name = trim(oci_result($s, 'NAME'));
			$remaining_amount = trim(oci_result($s, 'REMAINING_AMOUNT'));
			$starting_amount = trim(oci_result($s, 'STARTING_AMOUNT'));
			$staff_name = trim(oci_result($s2, 'NAME'));

			$out .= "<tr>";
			$out .= "<td>" . $name . "</td>";
			$out .= "<td>" . $starting_amount . "</td>";
			$out .= "<td>" . $remaining_amount . "</td>";
			$out .= "<td>" . $staff_name . "</td></tr>";
		}
	} elseif ($_GET['type'] == 'Program') {
		// Define the query.
		$q = "SELECT * FROM PROGRAM P JOIN ORGANIZES O ON P.PID = O.PID WHERE 1 = 1";
		if (isset($_GET['sid_dropdown'])) {
			$q .= " AND SID = {$_GET['sid_dropdown']}"; 
		}
		if (isset($_GET['event_name'])) {
			$q .= " AND EVENT_NAME = '{$_GET['event_name']}'"; 
		}
		if (isset($_GET['event_startDate'])) {
			$q .= " AND PDATE > TO_DATE('{$_GET['event_startDate']}', 'yyyy-mm-dd')"; 
		}
		if (isset($_GET['event_endDate'])) {
			$q .= " AND PDATE < TO_DATE('{$_GET['event_endDate']}', 'yyyy-mm-dd')"; 
		}

		echo $q;

		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		while(oci_fetch($s)){
			$organizers = "";

			$pid = oci_result($s, 'PID');
			$q2 = "SELECT NAME FROM STAFF_MEMBER S JOIN ORGANIZES O ON S.SID = O.SID WHERE O.PID = {$pid}";
			// Parse the query.
			$s2 = oci_parse($c, $q2);
			// Execute the query.
			oci_execute($s2);
			while (oci_fetch($s2)){
				$organizers .= trim(oci_result($s2, 'NAME')) . " ";
			}

			$event_name = trim(oci_result($s, 'EVENT_NAME'));
			$date = trim(oci_result($s, 'PDATE'));
			$time = trim(oci_result($s, 'TIME'));
			$location = trim(oci_result($s, 'LOCATION'));
			$funds_requested = trim(oci_result($s, 'FUNDS_REQUESTED'));

			$out .= "<tr>";
			$out .= "<td>" . $event_name . "</td>";
			$out .= "<td>" . $date . "</td>";
			$out .= "<td>" . $time . "</td>";
			$out .= "<td>" . $location . "</td>";
			$out .= "<td>" . $funds_requested . "</td>";
			$out .= "<td>" . $organizers . "</td></tr>";
		}
	} elseif ($_GET['type'] == 'Expense') {
		// Define the query.
		$q = "SELECT * FROM EXPENSE E JOIN USES U ON E.FORM_NUMBER = U.FORM_NUMBER";
		if (isset($_GET['form_number'])) {
			$q .= " WHERE FORM_NUMBER = {$_GET['form_number']}"; 
		}
		else { 
			$q .= " WHERE 1 = 1";
			if (isset($_GET['sid'])) {
				$q .= " AND SID = {$_GET['sid']}"; 
			}
			if (isset($_GET['start_date'])) {
				$q .= " AND EDATE > TO_DATE('{$_GET['start_date']}', 'yyyy-mm-dd')"; 
			}
			if (isset($_GET['end_date'])) {
				$q .= " AND EDATE < TO_DATE('{$_GET['end_date']}', 'yyyy-mm-dd')"; 
			}
			if (isset($_GET['outstanding']) && $_GET['outstanding'] == true) {
				$q .= " AND VENDOR IS NULL"; // i.e. will only be true if this
				// expense form is "outstanding" and has not been updated.
			}
		}

		echo $q;

		// Parse the query.
		$s = oci_parse($c, $q);
		// Execute the query.
		oci_execute($s);
		while(oci_fetch($s)){
			$users = "";
			$programs = "";
			$budgets = "";

			$form_number = oci_result($s, 'FORM_NUMBER');
			$q2 = "SELECT NAME FROM STAFF_MEMBER S JOIN USES U ON S.SID = U.SID WHERE U.FORM_NUMBER = {$form_number}";
			// Parse the query.
			$s2 = oci_parse($c, $q2);
			// Execute the query.
			oci_execute($s2);
			if (oci_fetch($s2)){
				$users .= trim(oci_result($s2, 'NAME'));
			}
			while (oci_fetch($s2)){
				$users .= ", " . trim(oci_result($s2, 'NAME'));
			}

			$q2 = "SELECT NAME FROM BUDGET B JOIN FUNDS F ON B.BID = F.BID WHERE F.FORM_NUMBER = {$form_number}";
			// Parse the query.
			$s2 = oci_parse($c, $q2);
			// Execute the query.
			oci_execute($s2);
			if (oci_fetch($s2)){
				$budgets .= trim(oci_result($s2, 'NAME'));
			}
			while (oci_fetch($s2)){
				$budgets .= ", " . trim(oci_result($s2, 'NAME'));
			}

			$q2 = "SELECT EVENT_NAME FROM PROGRAM P JOIN PAYSFOR PF ON PF.PID = P.PID WHERE PF.FORM_NUMBER = {$form_number}";
			// Parse the query.
			$s2 = oci_parse($c, $q2);
			// Execute the query.
			oci_execute($s2);
			if (oci_fetch($s2)){
				$programs .= trim(oci_result($s2, 'EVENT_NAME'));
			}
			while (oci_fetch($s2)){
				$programs .= ", " . trim(oci_result($s2, 'EVENT_NAME'));
			}

			$date = trim(oci_result($s, 'EDATE'));
			$vendor = trim(oci_result($s, 'VENDOR'));
			$max_amount = trim(oci_result($s, 'MAX_AMOUNT'));
			$amount = trim(oci_result($s, 'AMOUNT_SPENT'));
			$items_purchased = trim(oci_result($s, 'ITEMS_PURCHASED'));


			$out .= "<tr>";
			$out .= "<td>" . $form_number . "</td>";
			$out .= "<td>" . $date . "</td>";
			$out .= "<td>" . $vendor . "</td>";
			$out .= "<td>" . $max_amount . "</td>";
			$out .= "<td>" . $amount . "</td>";
			$out .= "<td>" . $users . "</td>";
			$out .= "<td>" . $budgets . "</td>";
			$out .= "<td>" . $programs . "</td></tr>";
		}
	}

	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo $out;
}
?>
