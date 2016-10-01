<?php
	session_start();

	require 'functions.php';
	if(isset($_COOKIE["admin"]))
	{
		// print_r($_POST);

		$days = "000000";
		foreach (current($_POST["day"]) as $key => $value) {
			$days[$key-1] = "1";
		}
		// echo $days;

		$teacher = array_values($_POST["teacher"]);

		if(strpos($_POST["subject"], "_") == true)
		{
			$sub = explode("_", $_POST["subject"]);
			$oldstaff = query("Select StaffId from Labs where SubjectId = '$sub[1]' and BatchId = '$sub[0]'");
			
			$res = query("Update Labs set StaffId = '$teacher[0]', Days = b'$days' where SubjectId = '$sub[1]' and BatchId = '$sub[0]'");

			if(gettype($res) == "array")
				$res = query("Update Notifs set StaffId = '$teacher[0]' where SubjectId = '$sub[1]' and StaffId = '".$oldstaff[0]["StaffId"]."'");
			else
			{
				echo json_encode(false);
				return;
			}
		}
		else
		{
			$oldstaff = query("Select StaffId from Lectures where SubjectId = '".$_POST["subject"]."'");
			
			$res = query("Update Lectures set StaffId = '$teacher[0]', Days = b'$days' where SubjectId = '".$_POST["subject"]."'");

			if(gettype($res) == "array")
				$res = query("Update Notifs set StaffId = '$teacher[0]' where SubjectId = '".$_POST["subject"]."' and StaffId = '".$oldstaff[0]["StaffId"]."'");
			else
			{
				echo json_encode(false);
				return;
			}
		}
		include 'cron_job.php';
		
		if(gettype($res) == "array")
			echo json_encode(true);
		else
			echo json_encode(false);
	}
	else
		echo json_encode("refresh");

?>