<?php
	session_start();

	require 'functions.php';

	if($_POST["username"]==ADMIN_ID && $_POST["password"] == ADMIN_PASS)
	{
		setcookie("admin", "0", time()+1800, "/");
		echo json_encode(true);
	}
	else
		echo json_encode(false);
?>