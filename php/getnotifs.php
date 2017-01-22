<?php
	require 'functions.php';

	$stmt = "Select s.Name, n.DateMissed from Notifs as n, Subjects as s where s.SubjectId = n.SubjectId and n.StaffId = '".$_COOKIE["uid"]."' order by n.SubjectId, n.DateMissed asc";

	$res = query($stmt);

	// print_r($res);
	if(empty($res))
	{
		echo json_encode(false);
		return;
	}

	for ($i=0, $n=sizeof($res); $i < $n; $i++) { 
		$ts = $res[$i]["DateMissed"];
		$res[$i]["DateMissed"] = date("D - M jS", strtotime($ts));
		// $res[$i]["dataDate"] = date("Y-m-d", strtotime($ts));
	}
	
	echo json_encode($res);
?>