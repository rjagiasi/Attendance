<?php
	require "functions.php";
	// print_r($_POST);
	$class = $_POST["classes"];
	$month = $_POST["month"];

	if(strpos($_POST["subject"], "_") == true)
	{
		//lab

		$sub = explode("_", $_POST["subject"]);
		$subject = $sub[1];
		$batch = $sub[0];

		$res = query("SELECT Day(Date) as date, StudentId, PA from Record Where Month(Date) = $month and SubjectId = $subject and StudentId in (SELECT StudentId from LabStudent Where ClassId = $class and BatchId = $batch) order by StudentId, date");

		$res2 = query("SELECT StudentId, Name, RollNo from Student Where ClassId = $class and StudentId in (SELECT StudentId from LabStudent Where ClassId = $class and BatchId = $batch) order by StudentId");

		$dates = query("SELECT distinct Day(Date) as date from Record Where Month(Date) = $month and SubjectId = $subject and StudentId in (SELECT StudentId from LabStudent Where ClassId = $class and BatchId = $batch) order by date");
		// print_r($res);
	}
	else
	{
		$subject = $_POST["subject"];

		$res = query("SELECT Day(Date) as date, StudentId, PA from Record Where Month(Date) = $month and SubjectId = $subject order by StudentId, date");

		$res2 = query("SELECT StudentId, Name, RollNo from Student Where ClassId = $class order by StudentId");

		$dates = query("SELECT distinct Day(Date) as date from Record Where Month(Date) = $month and SubjectId = $subject order by date");
	}
	
	// print_r($res);
	$list = array();

	$i=0;$j=0;
	foreach ($res2 as $key => $student) {
		
		$list[$j]["RollNo"] = $student["RollNo"];
		$list[$j]["Name"] = $student["Name"];

		while($res[$i]["StudentId"] == $student["StudentId"])
		{
			$list[$j][(string)$res[$i]["date"]] = ($res[$i]["PA"] == 1)?"P":"A";
			$i++;
		}
		$j++;
	}


	foreach ($dates as $key => $date) {
		$list[$j][$key] = $date["date"];
		// print_r($date["date"]);
	}

	// print_r($list);

	echo json_encode($list);

?>