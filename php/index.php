<?php
	session_start();

	require("functions.php");

	render("home.php", ["title" => "Home"] );
	// print_r($_SESSION);
?>