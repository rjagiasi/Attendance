<?php
	session_start();

	if(isset($_COOKIE["uid"]) && isset($_GET["class"]) && isset($_GET["branch"]))
	{
		require("functions.php");
		render("staff.php", ["title" => "Staff", "branch" => $_GET["branch"], "class" => $_GET["class"]] );
	}
	else
		header("Location: index.php");
?>