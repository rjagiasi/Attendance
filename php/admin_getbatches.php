<?php
	session_start();

	require 'functions.php';
	if(isset($_COOKIE["admin"]))
	{
		$res = query("SELECT distinct BatchId from LabStudent Where ClassId = '".$_POST["classes"]."'");

		if(gettype($res) == "array")
			echo json_encode($res);
		else
			echo json_encode(false);
	}
	else
		echo json_encode("refresh");

?>