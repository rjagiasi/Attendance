<?php
	
	require_once("functions.php");
	// $password = "admin";
	// $username = "admin";
	// // $salt = "123";
	// $salt = crypt($password);
	// // $password = crypt($password, $salt);
	// printf("\n%s\n%s\n", $salt, $password);

	$query = "Select temp.* from (select 
s.RollNo, r.SubjectId,

COUNT(CASE WHEN r.PA = 0x01 then 1 ELSE NULL END) as \"Pres\",
COUNT(CASE WHEN r.PA = 0x00 then 1 ELSE NULL END) as \"Abs\"

from `Attendance`.`Student` as s
join `Attendance`.`Record` as r
on s.StudentId = r.StudentId AND s.ClassId = 1 AND r.Date BETWEEN '2016-05-30' AND '2016-09-04'
group by r.SubjectId,r.StudentId) as temp
RIGHT JOIN Subjects as sub
ON sub.SubjectId = temp.SubjectId and sub.ClassId = 1
ORDER BY temp.RollNo, temp.SubjectId ASC";
	//query("INSERT INTO Staff (Name, Gender, Email, Username, Salt) VALUES('Rohan', '1', 'rjagiasi@gmail.com', '$username', '$salt')");
	$res = query($query);
	print_r($res);
	// $result = query("SELECT * FROM Staff WHERE username = \"$username\"");
	// print_r($result);
?>