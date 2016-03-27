<?php 
	// print_r($_POST);
	require_once 'functions.php';

	$class = $_POST["class"];
	$roll = $_POST["roll"];
	$classid = query("SELECT ClassId FROM Class WHERE Name = '$class'");
	$subid = query("SELECT Name, SubjectId FROM Subjects Where ClassId = '".$classid[0]["ClassId"]."'");
	$name = query("SELECT Name FROM Student Where ClassId='".$classid[0]["ClassId"]."' and RollNo='$roll'");

	$jsonarr["name"] = explode(" ", $name[0]["Name"])[1];
	// var_dump($name[0]["Name"]);

	for ($i=0, $n=sizeof($subid); $i < $n; $i++) { 
		
		$percentage = query("SELECT GetStudentReport('".$classid[0]["ClassId"]."', '".$roll."', '".$subid[$i]["SubjectId"]."') AS percent");
		// print(intval($i));
		$jsonarr[$subid[$i]["Name"]] = round($percentage[0]["percent"], 2);
	}

	// print($class[0]["ClassId"]);
	// echo("SELECT GetStudentReport('".$classid[0]["ClassId"]."', '".$roll."', '".$subid[0]["SubjectId"]."') AS percent");
	echo json_encode($jsonarr);
?>