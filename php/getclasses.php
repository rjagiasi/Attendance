<?php
	require_once 'functions.php';
	$dept = $_POST["dept"];
	$querystring = "Select ClassId, Name from Class where DeptId = (Select DeptId from Department where Name = '$dept')";
	$result = query($querystring);
	
	echo json_encode($result);
?>