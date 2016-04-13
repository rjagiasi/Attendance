<?php 
	require_once "functions.php";
	// print_r($_POST);
	$dept = $_POST["dept"];
	$class = $_POST["classes"];
	$sub = $_POST["subject"];
	$startdate = $_POST["startdate"];
	$enddate = $_POST["enddate"];

	$studentnames = query("SELECT RollNo, Name FROM Student Where ClassId = $class ORDER BY RollNo");

	// $students = query("SELECT RollNo, Name FROM Student Where ClassId = '$class'");
	if(empty($sub))
	{
		$res = query("Call GetClassReport('$startdate', '$enddate', $class)");
		$subjectnames = query("Call GetSubjects('$startdate', '$enddate', $class)");
	}
	else
	{
		$res = query("Call GetSubjectReport('$startdate', '$enddate', $class, $sub)");
		$subjectnames = query("SELECT Name, SubjectId FROM Subjects Where SubjectId = $sub");
	}
	
	for ($i=0, $n = count($studentnames),$m = count($subjectnames), $j = 0; $i < $n; $i++) { 
		$t = 0;
		$p = 0;
		foreach ($subjectnames as $key => $value) {
			$p += $res[$j]["Pres"];
			$lecttotal = $res[$j]["Pres"] + $res[$j]["Abs"];
			$t += $lecttotal;
			$studentnames[$i][$value["Name"]] = sprintf("%02d", $res[$j]["Pres"])."/".sprintf("%02d",$lecttotal)." : ".round((($res[$j]["Pres"]/$lecttotal)*100), 2);
			$j++;
		}
		if($m > 1)
		$studentnames[$i]["Total"] = sprintf("%02d", $p)."/".sprintf("%02d",$t)." : ".round((($p/$t)*100), 2);
	}

	// print_r($res);
	// echo count(studentnames);
	echo json_encode($studentnames);
?>