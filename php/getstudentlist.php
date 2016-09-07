<?php
	require_once 'functions.php';
	$class = $_POST["classes"];
	$date = $_POST["date"];
	$subjectid = $_POST["subject"];
	$islab = 0;

	if(strpos($subjectid, "_") == true)
	{

		$pieces = explode("_", $subjectid);
		$labid = $pieces[0];
		$subjectid = $pieces[1];
		$islab = 1;
		$checkdate = "SELECT NOT EXISTS (SELECT * FROM Record WHERE Date = '$date' AND SubjectId = '$subjectid' AND StudentId in (Select StudentId from LabStudent where LabId='$labid'))";
	}
	else
		$checkdate = "SELECT NOT EXISTS (SELECT * FROM Record WHERE Date = '$date' AND SubjectId = '$subjectid')";

	$res = query($checkdate);
	// print(current($res[0]));
	if(current($res[0]) == 1)
	{
		// $lectorlab = query("SELECT LectorLab from Subjects Where SubjectId = '$subjectid'");
		if($islab == 0)
			$querystring = "SELECT StudentId, Name, RollNo FROM Student WHERE ClassId = '$class'";
		else
			$querystring = "SELECT StudentId, Name, RollNo FROM Student WHERE ClassId = '$class' AND StudentId in (Select StudentId from LabStudent WHERE LabId='$pieces[0]')";
		$result = query($querystring);
		echo json_encode($result);
	}
	else
	{
		echo json_encode(false);
	}
?>