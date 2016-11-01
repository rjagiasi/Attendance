<?php
	require 'functions.php';

	$subid = $_POST["subject"];

	if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["reason"]))
	{
    	echo(json_encode(false));
		return;
	}

	if(strpos($subid, "_") == true)
		$subid = explode("_", $subid)[1];

	$stmt = "Insert into Cancelled (StaffId, SubjectId, Date, Reason) values ('".$_COOKIE["uid"]."', '$subid', '".$_POST["date"]."', '".$_POST["reason"]."')";

	$ret = query($stmt);

	if(gettype($ret) == "array")
	{
		query("Delete from Notifs where StaffId = '".$_COOKIE["uid"]."' and DateMissed = '".$_POST["date"]."' and SubjectId = '$subid'");
		echo json_encode(true);
	}
	else
		echo json_encode(false);
?>