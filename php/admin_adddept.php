<?php
	session_start();

	require 'functions.php';
	if(isset($_COOKIE["admin"]))
	{
		$res = query("Insert into Department (Name) values ('".strtoupper($_POST["dept"])."')");

		if(gettype($res) == "array")
			echo json_encode(true);
		else
			echo json_encode(false);
	}
	else
		echo json_encode("refresh");

?>