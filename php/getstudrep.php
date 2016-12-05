<?php

	require_once 'functions.php';
	// print_r($_POST);
	$classid = $_POST["classes"];
	$roll = $_POST["roll"];
	$name = $_POST["name"];
	// $classid = query("SELECT ClassId FROM Class WHERE Name = '$class'");
	if (preg_match('/[\'"^£$%&*()}{@#~?><>,|=_+¬-]/', $name))
	{
    	echo(json_encode("Tooo many records!"));
		return;
	}

	if(empty($name))
	{
		//roll no given
		echo json_encode(getResultTable($classid, $roll));
	}
	else if(empty($roll))
	{
		//name given
		$rollnumbers = query("SELECT RollNo from Student Where ClassId='".$classid."' and Name like '%".$_POST["name"]."%'");
		
		if (count($rollnumbers) == 0) 
		{
			echo json_encode("false");
			return;
		}
		elseif (count($rollnumbers) == 1) 
		{
			echo json_encode(getResultTable($classid, $rollnumbers[0]["RollNo"]));
		}
		elseif(count($rollnumbers) > 5)
		{
			echo(json_encode("Too many records!"));
			return;
		}
		else
		{
			$collapsibles = "<div class=\"panel-group\" id=\"accordion\">";
			$i = 1;
			foreach ($rollnumbers as $roll) {

				$name = query("SELECT Name FROM Student Where ClassId='".$classid."' and RollNo='".$roll["RollNo"]."'");
				
				$collapsibles.="<div class=\"panel panel-default\">
				<div class=\"panel-heading\">
				<h4 class=\"panel-title\">
				<a data-toggle=\"collapse\" data-parent=\"#accordion\" href=\"#collapse".$i."\">
				".$name[0]["Name"]."</a>
				</h4>
				</div>
				<div id=\"collapse".$i."\" class=\"panel-collapse collapse\">
      			<div class=\"panel-body\">";

				$collapsibles .= getResultTable($classid, $roll["RollNo"]);

				$collapsibles .= "</div></div></div>";

				$i++;
			}

			$collapsibles .= "</div>";
			echo json_encode($collapsibles);

		}
		
	}
	else
		echo json_encode("false");

	function getResultTable($classid, $roll)
	{
		$res = query("Call GetStudentReport(".$classid.", $roll)");
		// print_r($res);
		if(empty($res))
		{
			return "false";
		}
		else
		{
			$name = query("SELECT Name FROM Student Where ClassId='".$classid."' and RollNo='$roll'");
			$subnames = query("SELECT Name, SubjectId FROM Subjects Where ClassId = ".$classid." ORDER BY SubjectId ASC");
			// $n = explode(" ", $name[0]["Name"]);
			$jsonarr["name"] = $name[0]["Name"];
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
			$total = $fullpres + $fullab;

			$jsonarr["Total"] = sprintf("%02d", $fullpres)."/".sprintf("%02d",$total)." : ".round((($fullpres/$total)*100), 2)."%";

			$table = "<table class=\"table table-striped\"><tbody>";
			foreach($jsonarr as $key => $value) {
				$table .= "<tr><th>" . strtoupper($key) . "</th><td>" . $value . "</td>";
			}
			$table .= "</tbody></table>";

			return $table;
		}
	}
?>