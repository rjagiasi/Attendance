<?php
	session_start();

	require("functions.php");

	render("register_page.php", ["title" => "Register"] );
?>