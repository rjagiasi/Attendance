<?php 
	require_once 'functions.php';

	$res = query("Select * from Department");

	echo json_encode($res);
?>