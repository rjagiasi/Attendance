<?php 
	require_once "functions.php";
	// print_r($_POST);
	$dept = $_POST["dept"];
	$class = $_POST["classes"];
	$sub = $_POST["subject"];
	$startdate = $_POST["startdate"];
	$enddate = $_POST["enddate"];

	if(strpos($sub, "_") == true){
		$temp = explode("_", $sub);
		$sub = $temp[1];
		$batchid = $temp[0];
		$studentnames = query("SELECT RollNo, Name FROM Student Where ClassId = $class and StudentId in (SELECT StudentId from LabStudent Where BatchId = '$batchid' and ClassId = '$class') ORDER BY RollNo");
	}
	else
		$studentnames = query("SELECT RollNo, Name FROM Student Where ClassId = $class ORDER BY RollNo");

	if(empty($studentnames))
	{
		echo json_encode(false);
		return;
	}

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
	
	// print_r($subjectnames);
	// print_r($res);
	// print_r($studentnames);
	
	// print_r(array_flip($subjectnames));

	// print_r($SubjectLabReln);

	for ($i=0, $n = count($studentnames),$m = count($subjectnames), $j = 0; $i < $n; $i++) { 
		$t = 0;
		$p = 0;
		
		foreach ($subjectnames as $key => $value) {
			if ($res[$j]["SubjectId"] != $value["SubjectId"]) {
				$studentnames[$i][$value["Name"]] = "0/0 : 0";
				// $j++;
			}
			else
			{
				$p += $res[$j]["Pres"];
				$lecttotal = $res[$j]["Pres"] + $res[$j]["Abs"];
				$t += $lecttotal;
				$studentnames[$i][$value["Name"]] = sprintf("%02d", $res[$j]["Pres"])."/".sprintf("%02d",$lecttotal)." : ".round((($res[$j]["Pres"]/$lecttotal)*100), 2);
				$j++;
			}
		}
		if($m > 1)
			$studentnames[$i]["Total"] = sprintf("%02d", $p)."/".sprintf("%02d",$t)." : ".round((($p/$t)*100), 2);
	}

	// for ($i=0, $n=count($studentnames); $i < ; $i++) { 
		
	// }

	// print_r($res);
	// print_r($studentnames);
	// echo count(studentnames);
	echo json_encode($studentnames);
?>