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

			if($res == true || gettype($res) == "array")
				echo json_encode(true);
			else
				echo json_encode(false);
		}
		else
			echo json_encode("Fill form Correctly!");
	}
	else
		echo json_encode("refresh");
?>