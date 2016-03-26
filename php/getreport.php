<?php 
	require_once "functions.php";
	// print_r($_POST);
	$dept = $_POST["dept"];
	$class = $_POST["classes"];
	$sub = $_POST["subject"];

		$students = query("SELECT RollNo, Name FROM Student Where ClassId = '$class'");
		if(empty($sub))
		$subjectids = query("SELECT Name, SubjectId From Subjects Where ClassId = '$class'");
		else
		$subjectids = query("SELECT Name, SubjectId From Subjects Where ClassId = '$class' AND SubjectId = '$sub'");
		// foreach ($student as $key => $value) {
		// 	foreach ($subjectids as $key => $value) {
		// 		$percentage = query("SELECT GetStudentReport('$class', '2', '2') AS percent");
		// 	}
		// }
		for ($i=0, $n=sizeof($students); $i <  $n; $i++) {  
			for ($j=0, $m=sizeof($subjectids); $j < $m; $j++) { 
				$percentage = query("SELECT GetStudentReport('$class', '".$students[$i]["RollNo"]."', '".$subjectids[$j]["SubjectId"]."') AS percent");
				// print_r($percentage);
				$percentarr[$subjectids[$j]["Name"]] = round($percentage[0]["percent"], 2);
				
			}
			$jsonarr[$i] = array("roll" => $students[$i]["RollNo"], "name" => $students[$i]["Name"], "percent" => $percentarr);
		}
		

	// print_r($jsonarr);
	echo json_encode($jsonarr);
?>