<?php
	session_start();

	require 'functions.php';
	if(isset($_COOKIE["admin"]))
	{
		
		if((sizeof($_POST["day"]) == sizeof($_POST["teacher"])) && isset($_POST["subject"]))
		{
			$teacher = array_values($_POST["teacher"]);
			$day = array_values($_POST["day"]);
			// print_r($teacher);
			// print_r($day);
			// return;

			if(isset($_POST["haslab"]))
			{
				//labs
				$res = query("Insert into Subjects (Name, ClassId, LectorLab) values ('".$_POST["subject"]."_lab', '".$_POST["classes"]."', b'1')");
				if(gettype($res) != "array")
				{
					echo json_encode("Subject already exists!");
					return;
				}
				$sub_id = query("SELECT SubjectId from Subjects where Name='".$_POST["subject"]."_lab'");

				for ($i=1, $n = sizeof($teacher); $i < $n; $i++) { 
					$days = "000000";
					foreach ($day[$i] as $key => $value) {
						$days[$key-1] = "1";
					}
			// print_r($sub_id);
			// return;
					$qs = "Insert into Labs (SubjectId, BatchId, StaffId, Days) values ('".$sub_id[0]["SubjectId"]."', '$i', '".$teacher[$i]."', b'$days')";
			// echo($qs);
					$res = query($qs);

					if(gettype($res) != "array")
					{
						echo json_encode("Some Error Occurred!");
						return;
					}
						
				}
				// $teacher = 
			}
			//lect
			
			$res = query("Insert into Subjects (Name, ClassId, LectorLab) values ('".$_POST["subject"]."', '".$_POST["classes"]."', b'0')");
			if(gettype($res) != "array")
			{
				echo json_encode("Subject already exists!");
				return;
			}

			$sub_id = query("SELECT SubjectId from Subjects where Name='".$_POST["subject"]."'");
			$days = "000000";
			foreach ($day[0] as $key => $value) {
				$days[$key-1] = "1";
			}
			// print_r($sub_id);
			// return;
			$qs = "Insert into Lectures (SubjectId, StaffId, Days) values ('".$sub_id[0]["SubjectId"]."', '$teacher[0]', b'$days')";
			// echo($qs);
			$res = query($qs);

			if(gettype($res) == "array")
			{
				echo json_encode(true);
				return;
			}
			else
				echo json_encode("Some Error Occurred!");
		}
		else
			echo json_encode("Enter Subjects properly!");
		
	}
	else
		echo json_encode("refresh");

?>