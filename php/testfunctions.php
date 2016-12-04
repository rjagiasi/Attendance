<?php
	
	require_once("functions.php");
	// $password = "admin";
	// $username = "admin";
	// echo(crypt($password));
	// // $salt = "123";
	// $salt = crypt($password);
	// // $password = crypt($password, $salt);
	// printf("\n%s\n%s\n", $salt, $password);
	$values = json_decode($_COOKIE["cust_menu"], true);
	print_r($_COOKIE["cust_menu"]);
	$str = "";
	foreach ($values as $sub) {
		$str .= "<li class=".$sub["c"]." data-branch=".$sub["d"]."><a>".$sub["c"]."</a></li>";
	}
	echo($str);
	// echo (true xor true);

	//query("INSERT INTO Staff (Name, Gender, Email, Username, Salt) VALUES('Rohan', '1', 'rjagiasi@gmail.com', '$username', '$salt')");

	// $result = query("SELECT * FROM Staff WHERE username = \"$username\"");
	// print_r($result);
?>