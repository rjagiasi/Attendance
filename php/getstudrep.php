<?php 
	
	require_once 'functions.php';

	$class = $_POST["class"];
	$roll = $_POST["roll"];
	$classid = query("SELECT ClassId FROM Class WHERE Name = '$class'");

	$res = query("Call GetStudentReport(".$classid[0]["ClassId"].", $roll)");
	$name = query("SELECT Name FROM Student Where ClassId='".$classid[0]["ClassId"]."' and RollNo='$roll'");
	$subnames = query("SELECT Name, SubjectId FROM Subjects Where ClassId = ".$classid[0]["ClassId"]." ORDER BY SubjectId ASC");
	$jsonarr["name"] = explode(" ", $name[0]["Name"])[1];
	
	$j=0;
	foreach ($subnames as $key => $value) {
		$lecttotal = $res[$j]["Pres"] + $res[$j]["Abs"];
		$jsonarr[$value["Name"]] = sprintf("%02d", $res[$j]["Pres"])."/".sprintf("%02d",$lecttotal)." : ".round((($res[$j]["Pres"]/$lecttotal)*100), 2);
		$j++;
	}
	
	echo json_encode($jsonarr);
?>