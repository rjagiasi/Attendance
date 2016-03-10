<?php
	require_once("functions.php");
	$querystring = "INSERT INTO Student (Name, ClassId, RollNo) VALUES ";
	$values = "";
	$file = fopen("D10_names.csv", "r");
	$line = split(",", fgets($file));
	$line[1] = str_replace("\r\n", "", $line[1]);
	$values = $values . "('$line[1]', 1, $line[0])";

	while(!feof($file))
	{
		$line = split(",", fgets($file));
		$roll = $line[0];
		$name = $line[1];
		$name = str_replace("\r\n", "", $name);
		$values = $values . ",('$name', '1', '$roll')";
	}
	$querystring = $querystring.$values;
	print($querystring);

	query($querystring);
	fclose($file);
?>