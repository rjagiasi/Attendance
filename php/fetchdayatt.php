<?php
	require_once 'functions.php';

	$classid = $_POST["classes"];
	$roll = $_POST["roll"];
	$subject = $_POST["subject"];

	if(strpos($subject, "_") == true)
		$subject = explode("_", $subject)[1];
		

	$date = $_POST["date"];
	$studid = query("Select StudentId, Name From Student Where ClassId = $classid AND RollNo = $roll");

	$res = query("Select PA From Record Where SubjectId = $subject AND Date = '$date' AND StudentId = ".$studid[0]["StudentId"]);
	

	if(empty($res))
		echo json_encode("false");
	else
	{
		$res[0]["Name"] = $studid[0]["Name"];
		echo json_encode($res);
	}
		
?>