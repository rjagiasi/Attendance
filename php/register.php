<?php 
	// return true;
	require_once("functions.php");
	
	$name = $_POST["name"];
	$gender = $_POST["gender"];
	$email = $_POST["email"];
	$username = $_POST["username"];
	$salt = crypt($_POST["pass"]);

	$querystring = "INSERT INTO Staff (Name, Gender, Email, Username, Salt) VALUES ('$name', b'$gender', '$email', '$username', '$salt')";
	// console.log($querystring);
	// var_dump($querystring);
	// print($querystring);
	// $q = "SELECT * FROM Staff";
	$reply =  query($querystring);
	// echo $reply;
	if(gettype($reply) == "array")
		echo json_encode(true);
	else
		echo json_encode(false);
?>