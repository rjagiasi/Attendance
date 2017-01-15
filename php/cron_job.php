<?php
	
	require 'functions.php';
	$date = date('Y-m-d');
	// echo($date);
	$day = date('N');
	
	if($day == 7)
		return;

	$days = "000000";
	$days[$day-1] = "1";
	// echo $days;


	$lects = query("Select SubjectId, StaffId from Lectures where (Days & $days) > 0 and SubjectId");
					
	$labs = query("Select l.SubjectId, s.ClassId, l.BatchId, l.StaffId from Labs as l, Subjects as s where (l.Days & b'$days') > 0 and l.SubjectId = s.SubjectId");
	// echo $lects;
	// print_r($labs);
	// return;
	$insert_stmt = "Insert into Notifs (SubjectId, DateMissed, StaffId) VALUES ";
	$values = "";

	foreach ($lects as $key => $value) {
		$ret = query("Select exists (Select * from Record where Date = '$date' and SubjectId = '".$value["SubjectId"]."')");
		$ret2 = query("Select exists (Select * from Cancelled where Date = '$date' and SubjectId = '".$value["SubjectId"]."')");
		// print_r(current($ret2[0]));
		if(current($ret[0]) == 0 && current($ret2[0]) == 0)	//if attendance not present and lec not cancelled
		{
			$values .= ",('".$value["SubjectId"]."', '$date', '".$value["StaffId"]."')";
		}
	}

	foreach ($labs as $key => $value) {
		$ret = query("Select exists (Select * from Record where Date = '$date' and SubjectId = '".$value["SubjectId"]."' and StudentId in (Select StudentId from LabStudent where ClassId = '".$value["ClassId"]."' and BatchId = '".$value["BatchId"]."'))");
		$ret2 = query("Select exists (Select * from Cancelled where Date = '$date' and SubjectId = '".$value["SubjectId"]."')");
		// print_r($ret);
		if(current($ret[0]) == 0 && current($ret2[0]) == 0)	//if attendance not present
		{
			$values .= ",('".$value["SubjectId"]."', '$date', '".$value["StaffId"]."')";
		}
	}

	$insert_stmt .= trim($values, ",");
	$insert_stmt .= " on duplicate key update StaffId = values (StaffId)";
	// echo $insert_stmt;
	// return;
	query($insert_stmt);

	$mailing_list = query("Select Email, Name from Staff where StaffId in (Select StaffId from Notifs group by StaffId having count(*) > 8)");

	foreach ($mailing_list as $key => $value) {
		sendmail("Data Remaining", "A lot of attendance data has not yet been added from your side. Please log into the system and add the data immediately.", $value["Email"], $value["Name"]);
	}
?>