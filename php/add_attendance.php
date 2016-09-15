<?php
	require_once 'functions.php';
	$date = array_pop($_POST);
	$subjectid = array_pop($_POST);
	$islab = 0;

	if(strpos($subjectid, "_") == true)
	{
		$subjectid = explode("_", $subjectid)[1];
		$islab = 1;
	}
		

	$querystring = "INSERT INTO Record (Date, StudentId, SubjectId, PA) VALUES ";

	foreach ($_POST as $key => $value) {
		$studentid = explode("_", $key)[1];
		$querystring .= "('$date', '$studentid', '$subjectid', b'$value'),";
	}
	$querystring = rtrim($querystring, ",");
	// print($querystring);
	$result = query($querystring);
	$allpresent = 1;

	if($islab == 1)
	{
		$day = date('N', strtotime($date));
		$days = "000000";
		$days[$day-1] = "1";
		// echo($day);
		
		$labresult = query("Select l.SubjectId, s.ClassId, l.BatchId from Labs as l, Subjects as s where (l.Days & $days) > 0 and l.SubjectId = s.SubjectId and l.SubjectId = '$subjectid' and l.StaffId = '".$_COOKIE["uid"]."'");

		if (sizeof($labresult) > 1) {
			foreach ($labresult as $key => $value) 
			{
				$ret = query("Select exists (Select * from Record where Date = '$date' and SubjectId = '".$value["SubjectId"]."' and StudentId in (Select StudentId from LabStudent where ClassId = '".$value["ClassId"]."' and BatchId = '".$value["BatchId"]."'))");
				// print current($ret[0]);
				$allpresent &= current($ret[0]);
				// print_r($allpresent);
			}
		}

		// print_r($labresult);
	}

	if($allpresent == 1)
		query("Delete from Notifs where SubjectId = '$subjectid' and DateMissed = '$date' and StaffId = '".$_COOKIE["uid"]."'");
	
	if(gettype($result) == "array")
		echo json_encode(true);
	else
		echo json_encode(false);
	
	
?>