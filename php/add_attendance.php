<?php
	require_once 'functions.php';
	$date = array_pop($_POST);
	$subjectid = array_pop($_POST);
	array_pop($_POST);
	array_pop($_POST);
	
	$querystring = "INSERT INTO Record (Date, StudentId, SubjectId, PA) VALUES ";

	foreach ($_POST as $key => $value) {
		$studentid = explode("_", $key)[1];
		$querystring .= "('$date', '$studentid', '$subjectid', b'$value'),";
	}
	$querystring = rtrim($querystring, ",");
	$result = query($querystring);

	if(gettype($result) == "array")
		echo json_encode(true);
	else
		echo json_encode(false);
	
	
?>