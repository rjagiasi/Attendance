<?php
	
	require_once("functions.php");
	$password = "admin";
	$username = "admin";
	// $salt = "123";
	$salt = crypt($password);
	// $password = crypt($password, $salt);
	printf("\n%s\n%s\n", $salt, $password);
	//query("INSERT INTO Staff (Name, Gender, Email, Username, Salt) VALUES('Rohan', '1', 'rjagiasi@gmail.com', '$username', '$salt')");

	// $result = query("SELECT * FROM Staff WHERE username = \"$username\"");
	// print_r($result);
?>