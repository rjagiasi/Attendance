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
			// $host  = $_SERVER["HTTP_HOST"];
			// $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			// $url = 
			// $extra = "staff.php";
			$_SESSION["uid"] = $result[0]["StaffId"];
			$_SESSION["name"] = $result[0]["Name"];
			$_SESSION["username"] = $result[0]["Username"];
			header("Location: ".$_SERVER["HTTP_REFERER"]);
			// header("Refresh:0");
		}
		else
		{
			// $error = "Invalid Username Or Password";
			$url = parse_url($_SERVER["HTTP_REFERER"]);
			parse_str($url["query"], $params);
			$params["error"] = urlencode("Invalid Username Or Password");
			$url["query"] = http_build_query($params);
			$url = $url["scheme"]."://".$url["host"].$url["path"]."?".$url["query"];
			print_r($url);
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