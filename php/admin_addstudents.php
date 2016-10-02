<?php
	session_start();

	require 'functions.php';
	// print_r($_POST);
	// print_r($_FILES);
	// echo insert_stud($_FILES['bulkdata']['tmp_name'], $_POST["classes"]);
	
	if(empty($_COOKIE["admin"]))
	{
		if((!empty($_FILES['bulkdata']['tmp_name']) && $_FILES['bulkdata']['type'] == "text/csv") xor (!empty($_POST["name"] && !empty($_POST["rollno"]))))
		{
			// echo "hello";
			if(!empty($_FILES['bulkdata']['tmp_name']))
				$res = insert_stud($_FILES['bulkdata']['tmp_name'], $_POST["classes"]);
			else
				$res = query("Insert into Student (Name, ClassId, RollNo) values ('".$_POST["name"]."', '".$_POST["classes"]."', '".$_POST["rollno"]."')");

			if($res == true)
				echo json_encode(true);
			else if(gettype($res) == "array")
			{
				$studid = query("Select StudentId from Student Where ClassId = '".$_POST["classes"]."' and RollNo = '".$_POST["rollno"]."'");
				$res = query("Insert into LabStudent (ClassId, BatchId, StudentId) values ('".$_POST["classes"]."', '".$_POST["batch"]."', '".$studid[0]["StudentId"]."')");
				if(gettype($res) == "array")
					echo json_encode(true);
				else
					echo json_encode("Couldn't add to Batch.");
			}
			else
				echo json_encode("Roll No already Exists!");
		}
		else
			echo json_encode("Fill form Correctly!");
	}
	else
		echo json_encode("refresh");
?>