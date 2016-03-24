<?php
	require_once 'functions.php';
	session_start();
	$class = $_POST["class"];
	$sid = $_SESSION["uid"];
	$querystring = "Select SubjectId, Name from Subjects where StaffId='$sid' AND ClassId = '$class'";
	$result = query($querystring);
	
	echo json_encode($result);
	// print_r($class);

?>