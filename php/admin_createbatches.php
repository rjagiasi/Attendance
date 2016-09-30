<?php
	session_start();

	require 'functions.php';
	if(isset($_COOKIE["admin"]))
	{
		
		$classid = array_pop($_POST);

		// $res = query("SELECT StudentId, Name, RollNo From Student Where ClassId = '".$_POST["classes"]."'");

		$querystmt = "Insert into LabStudent (ClassId, StudentId, BatchId) values ";
		$values = "";

		foreach ($_POST["batchid"] as $key => $value) {
			$values .= ",('$classid', '$key', '$value')";
			// print($key);
		}
		$querystmt .= trim($values, ",");
		$querystmt .= " on duplicate key update BatchId = values (BatchId)";

		// print($querystmt);
		$res = query($querystmt);

		if(gettype($res) == "array")
			echo json_encode(true);
		else
			echo json_encode(false);
	}
	else
		echo json_encode("refresh");

?>