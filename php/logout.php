<?php
	session_start();
	setcookie("uid", "", time()-1,"/");
	setcookie("name", "", time()-1,"/");
	setcookie("username", "", time()-1,"/");
	setcookie("cust_menu", "", time()-1,"/");
	setcookie("admin", "", time()-1, "/");
	session_destroy();
	header("Location: index.php");
?>