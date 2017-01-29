<?php
	require_once 'functions.php';
	$class = $_POST["classes"];
	$date = $_POST["date"];
	$subjectid = $_POST["subject"];
	$lec_no = $_POST["lec_no"];
	$islab = 0;

	if(strpos($subjectid, "_") == true)
	{

		$pieces = explode("_", $subjectid);
		$batchid = $pieces[0];
		$subjectid = $pieces[1];
		$islab = 1;
		$checkdate = "SELECT NOT EXISTS (SELECT * FROM Record WHERE Date = '$date' AND SubjectId = '$subjectid' AND Lec_no = '$lec_no' AND StudentId in (Select StudentId from LabStudent where BatchId='$batchid' and ClassId='$class'))";
	}
	else
		$checkdate = "SELECT NOT EXISTS (SELECT * FROM Record WHERE Date = '$date' AND SubjectId = '$subjectid' AND Lec_no = '$lec_no')";

	$res = query($checkdate);

	$res2 = query("SELECT NOT EXISTS (Select * from Cancelled where StaffId = '".$_COOKIE["uid"]."' and Date = '$date' and SubjectId = '$subjectid')");

	// print(current($res[0]));
	if(current($res[0]) == 1 && current($res2[0]) == 1)
	{
		// $lectorlab = query("SELECT LectorLab from Subjects Where SubjectId = '$subjectid'");
		if($islab == 0)
			$querystring = "SELECT StudentId, Name, RollNo FROM Student WHERE ClassId = '$class'";
		else
			$querystring = "SELECT StudentId, Name, RollNo FROM Student WHERE ClassId = '$class' AND StudentId in (Select StudentId from LabStudent WHERE ClassId='$class' and BatchId='$batchid')";
		$result = query($querystring);
		echo json_encode($result);
	}
	else
	{
		echo json_encode(false);
	}
?>