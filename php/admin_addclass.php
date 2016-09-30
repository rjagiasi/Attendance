<?php
	session_start();

	require 'functions.php';
	if(isset($_COOKIE["admin"]))
	{
		$res = query("Insert into Class (Name, DeptId) values ('".strtoupper($_POST["class"])."', '".$_POST["dept"]."')");

		if(gettype($res) == "array")
			echo json_encode(true);
		else
			echo json_encode(false);
	}
	else
		echo json_encode("refresh");

?>