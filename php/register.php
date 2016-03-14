<?php 
	// return true;
	require_once("functions.php");
	// error_reporting(E_ALL);

	// function query()
	// {
		
	// }

	// print_r($_POST);
	$name = $_POST["name"];
	$gender = $_POST["gender"];
	$email = $_POST["email"];
	$username = $_POST["username"];
	$salt = crypt($_POST["pass"]);

	$querystring = "INSERT INTO Staff (Name, Gender, Email, Username, Salt) VALUES('$name', '$gender', '$email', '$username', '$salt')";
	// console.log($querystring);
	// var_dump($querystring);
	// print($querystring);
	// $q = "SELECT * FROM Staff";
	$reply =  query($querystring);
	// print_r($reply);
	if(gettype($reply) == "array")
		echo json_encode(true);
	else
		echo json_encode(false);
?>