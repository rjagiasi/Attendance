
<?php
	session_start();

	require "functions.php";
	
	if ( $_SERVER["REQUEST_METHOD"] == "POST" ) 
	{
		$password = $_POST["password"]; 
		$username = $_POST["username"];

		$result = query( "SELECT * FROM Staff WHERE username = \"$username\"" );
		// print($username);

		print_r($result);
		$password = crypt($password, $result[0]["Salt"]);
		// printf("\n%s", $password);
		// printf("\n%s\n%s\n", $salt, $password);

		if(($result[0]["Role"] == 1) && hash_equals($result[0]["Salt"], $password))
		{
			// print_r($_SERVER);
			// $host  = $_SERVER["HTTP_HOST"];
			// $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			// $url = 
			// $extra = "staff.php";

			$res = query("CALL GetClasses(".$result[0]["StaffId"].")");
			setcookie("admin", "0", time()+(3600), "/");
			setcookie("uid", $result[0]["StaffId"], time()+(86400*10),"/");
			setcookie("name", $result[0]["Name"], time()+(86400*10),"/");
			// setcookie("username", $result[0]["Username"], time()+(86400*10),"/");
			setcookie("cust_menu", json_encode($res), time()+(86400*10),"/");
			header("Location: ".explode("?",$_SERVER["HTTP_REFERER"])[0]);
			// print($_GET["branch"]);
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