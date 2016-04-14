<?php
	session_start();

	if(isset($_SESSION["uid"]))
	{
		require("functions.php");
		render("staff.php", ["title" => "Staff"] );
	}
	else
		header("Location: index.php");
?>