<?php
	session_start();

	require 'functions.php';
	
	if(empty($_COOKIE["admin"]))
	{
		$res = "";
		if(strpos($_POST["subject"], "_"))
		{
			$sub = explode("_", $_POST["subject"]);
			$res = query("Select StaffId, Days from Labs where SubjectId = '$sub[1]' and BatchId = '$sub[0]'");
		}
		else
		{
			$res = query("Select StaffId, Days from Lectures where SubjectId = '".$_POST["subject"]."'");
		}
		echo json_encode($res);
	}
	else
		echo json_encode("refresh");
?>