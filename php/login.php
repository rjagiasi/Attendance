<?php
	session_start();

	require "functions.php";
	
	if ( $_SERVER["REQUEST_METHOD"] == "POST" ) 
	{
		$password = $_POST["password"]; 
		$username = $_POST["username"];
		$result = query( "SELECT * FROM Staff WHERE username = \"$username\"" );
		// print($username);
		// print_r($result);
		$password = crypt($password, $result[0]["Salt"]);
		// printf("\n%s", $password);
		// printf("\n%s\n%s\n", $salt, $password);

		if(hash_equals($result[0]["Salt"], $password))
		{
			// print_r($_SERVER);
			$host  = $_SERVER["HTTP_HOST"];
			$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			$extra = "staff.php";
			$_SESSION["uid"] = $result[0]["StaffId"];
			$_SESSION["name"] = $result[0]["Name"];
			$_SESSION["username"] = $result[0]["Username"];
			// header("Location: http://$host$uri/$extra");
			header("Refresh:0");
		}
		else
		{
			$error = "Invalid_Username_Or_Password";
			$url = $_SERVER["HTTP_REFERER"];
			if(strpos($url,'?') !== false) {
				$url .= '&error='.$error;
			} else {
				$url .= '?error='.$error;
			}
			header("Location: $url");
		}
	}
	else
	{
		$host  = $_SERVER["HTTP_HOST"];
		$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
		$extra = "index.php";
		header("Location: http://$host$uri/$extra");
	}
?>