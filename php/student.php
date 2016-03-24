<?php
	session_start();

	require("functions.php");

	render("student.php", ["title" => "Student", "branch" => $_GET["branch"], "class" => $_GET["class"]] );
?>