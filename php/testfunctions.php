<?php
	
	require_once("functions.php");
	// $password = '123';
	// $user = 'root';
	// $salt = crypt($password);
	// printf("\n%s\n%s\n", $salt, crypt($password, $salt));
	// query("INSERT INTO Users (name, pswd, salt) VALUES('root', '$pass', '$salt')");
	$result = query("Select * from Class");
	// $salt = $result[0]['salt'];
	// $pass = crypt($password, $salt);

	print_r($result);
?>