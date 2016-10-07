<?php

require_once 'functions.php';
// print_r($_POST);
$classid = $_POST["classes"];
$roll = $_POST["roll"];
// $classid = query("SELECT ClassId FROM Class WHERE Name = '$class'");

$res = query("Call GetStudentReport(".$classid[0]["ClassId"].", $roll)");
// print_r($res);
if(empty($res))
	echo json_encode("false");
else
{
	$name = query("SELECT Name FROM Student Where ClassId='".$classid[0]["ClassId"]."' and RollNo='$roll'");
	$subnames = query("SELECT Name, SubjectId FROM Subjects Where ClassId = ".$classid[0]["ClassId"]." ORDER BY SubjectId ASC");
	$n = explode(" ", $name[0]["Name"]);
	$jsonarr["name"] = $n[0]." ".$n[1];
	$fullab=0;
	$fullpres=0;
	$j=0;
	foreach ($subnames as $key => $value) {
		if($res[$j]["SubjectId"] != $value["SubjectId"])
			$jsonarr[$value["Name"]] = "00/00 : 0%";
		else
		{
			$fullab+=$res[$j]["Abs"];
			$fullpres+=$res[$j]["Pres"];
			$lecttotal = $res[$j]["Pres"] + $res[$j]["Abs"];
			$jsonarr[$value["Name"]] = sprintf("%02d", $res[$j]["Pres"])."/".sprintf("%02d",$lecttotal)." : ".round((($res[$j]["Pres"]/$lecttotal)*100), 2)."%";
			$j++;
		}
	}
	
	echo json_encode($jsonarr);
}
?>