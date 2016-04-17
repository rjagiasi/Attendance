<?php
	// print_r($_POST);
	session_start();
	require "functions.php";

	if(isset($_SESSION["uid"]))
	{
		$password = $_POST["oldpass"]; 
		$res = query("SELECT Salt FROM Staff WHERE StaffId = ".$_SESSION["uid"]);
		$password = crypt($password, $res[0]["Salt"]);
		// print_r($res);
		// echo $password;
		if(hash_equals($res[0]["Salt"], $password))
		{
			$dbpass = crypt($_POST["pass"]);

			$res = query("UPDATE Staff SET Salt = '$dbpass' WHERE StaffId = ".$_SESSION["uid"]);
			
			if(gettype($res) == "array")
				echo json_encode(true);
			else
				echo json_encode(false);
		}
		else
			echo json_encode(false);
		
	}
	else
		header("Location: index.php");
?>