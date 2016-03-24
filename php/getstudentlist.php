<?php
	require_once 'functions.php';
	$class = $_POST["classes"];
	$date = $_POST["date"];
	$subjectid = $_POST["subject"];
	$checkdate = "SELECT NOT EXISTS (SELECT * FROM Record WHERE Date = '$date' AND SubjectId = '$subjectid')";
	$res = query($checkdate);
	// print(current($res[0]));
	if(current($res[0]) == 1)
	{
		$querystring = "SELECT StudentId, Name, RollNo FROM Student WHERE ClassId = '$class'";
		$result = query($querystring);
		echo json_encode($result);
	}
	else
	{
		echo json_encode(false);
	}
?>