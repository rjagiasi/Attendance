<?php
	require "functions.php";
	// print_r($_POST);
	$class = $_POST["classes"];
	$month = $_POST["month"];
	$subject = strpos($_POST["subject"], "_")?explode("_", $_POST["subject"])[1]:$_POST["subject"];

	$res = query("SELECT Day(Date) as date, StudentId, PA from Record Where Month(Date) = $month and SubjectId = $subject order by StudentId, date");

	$res2 = query("SELECT StudentId, Name, RollNo from Student Where ClassId = $class order by StudentId");
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

	// print_r($list);

	$dates = query("SELECT distinct Day(Date) as date from Record Where Month(Date) = $month and SubjectId = $subject order by date");

	// print_r($dates);

	foreach ($dates as $key => $date) {
		$list[$j][$key] = $date["date"];
		// print_r($date["date"]);
	}

	// print_r($list);

	echo json_encode($list);

?>