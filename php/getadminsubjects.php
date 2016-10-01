<?php
	require_once 'functions.php';
	session_start();
	$class = $_POST["class"];

	$querystring = "Select SubjectId, Name from Subjects where ClassId = '$class' and LectorLab = 0";
	$result = query($querystring);
	// print_r($result);

	$qs2 = "Select l.SubjectId, l.BatchId, s.Name from Labs as l, Subjects as s where s.ClassId = '$class' and s.SubjectId = l.SubjectId";
	$res2 = query($qs2);
	// print_r($res2);

	$i = sizeof($result);

	foreach ($res2 as $key => $value) {
		$result[$i]["SubjectId"] = $value["BatchId"]."_".$value["SubjectId"];
		$result[$i]["Name"] = $value["Name"]." ".(chr($value["BatchId"]+ord('A')-1));
		$i++;
	}
	// print_r($i);
	// print_r($result);
	echo json_encode($result);
?>