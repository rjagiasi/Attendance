<?php
	require "functions.php";
	// print_r($_POST);
	if($_POST["currval"] == "0")
		$newbit = "1";
	else
		$newbit = "0";
	$roll = $_POST["roll"];
	$class = $_POST["classes"];

	$subid = $_POST["subject"];
	if(strpos($subid, "_") == true)
		$subid = explode("_", $subid)[1];

	$studid = query("SELECT StudentId FROM Student WHERE ClassId = '$class' AND RollNo = '$roll'");
	$querystring = "UPDATE Record Set PA = b'$newbit' where Date = '".$_POST["date"]."' and StudentId = '".$studid[0]["StudentId"]."' AND SubjectId = '".$subid."' and Lec_no = '".$_POST["lec_no"]."'";
	$res=query($querystring);
	// echo $res;
	if(gettype($res) == "array")
		echo json_encode(true);
	else
		echo json_encode(false);
	// print_r($studid);
?>