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
		
	}

	// Close the connection.
	oci_close($c);

	// Return a message indicating the status.
	echo $out;
}
?>
