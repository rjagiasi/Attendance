<?php
	require_once 'functions.php';
	$class = $_POST["classes"];
	$querystring = "SELECT StudentId, Name, RollNo FROM Student WHERE ClassId = '$class'";
	$result = query($querystring);
	echo json_encode($result);
?>