<?php
	
	require 'functions.php';

	$user = $_POST["fp_username"];
	$email = query("SELECT Name, Email FROM Staff WHERE Username='$user'");
	
	if(empty($email[0]["Email"]))
		echo json_encode("false");
	else
	{

		$newpassword = bin2hex(openssl_random_pseudo_bytes(5, $cstrong));

		$dbpass = crypt($newpassword);

		$subject = 'Password Reset - Attendance';

		$body = 'Your new Password is '.$newpassword;

		if (!sendmail($subject, $body, $email[0]["Email"], $email[0]["Name"]))
		{
			echo json_encode("0");
		}
		else
		{
			query("UPDATE Staff SET Salt = '$dbpass' WHERE Username='$user'");
			echo json_encode("1");
		}
	}
?>