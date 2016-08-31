<?php

require_once("constants.php");

function query()
{
	$sql = func_get_arg(0);

	$conn = new mysqli(SERVER, USERNAME, PASSWORD);

	if (!$conn)
	{
		die("Connection failed: " . mysqli_connect_error());
	}
	else
	{
		mysqli_select_db($conn, DATABASE);
		$result = mysqli_query($conn, $sql);
		if($result == TRUE)
		{

		$array_2d = [];
		if(gettype($result) == "object")
			while($array = mysqli_fetch_array($result, MYSQL_ASSOC))
			{
				$array_2d[] = $array;
			}
			return $array_2d;
		}
		else
		{
			// print("Query Failed\n");
			return false;
		}
	}
}

function render($template, $values = [])
{
	if (file_exists("../html/$template"))
	{
		extract($values);

		require("../html/header.php");

		require("../html/$template");

		require("../html/footer.php");
	}
	else
	{
		trigger_error("Invalid template: $template", E_USER_ERROR);
	}
}

function sendmail($subject, $body, $to, $to_name)
{
	date_default_timezone_set('Etc/UTC');

	require 'PHPMailerAutoload.php';
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
	$mail->Host = 'smtp.mail.yahoo.com';

	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	
	$mail->Port = 25;	// port 25 for yahoo
	// $mail->Port = 587; //for gmail

	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = EMAIL_ID;
	//Password to use for SMTP authentication
	$mail->Password = EMAIL_PASSWORD;
	//Set who the message is to be sent from
	$mail->setFrom(EMAIL_ID, 'VESIT Attendance');

	//Set an alternative reply-to address
	// $mail->addReplyTo('replyto@example.com', 'First Last');

	//Set who the message is to be sent to
	$mail->addAddress($to, $to_name);

	//Set the subject line
	$mail->Subject = $subject;

	//Read an HTML message body from an external file, convert referenced images to embedded,
	//convert HTML into a basic plain-text alternative body
	// $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
	//Replace the plain text body with one created manually

	$mail->Body = $body;

	//Attach an image file
	// $mail->addAttachment('images/phpmailer_mini.png');
	//send the message, check for errors

	// print_r($mail);

	if (!$mail->send()) {
		return false;
	} else {
		return true;
	}
}
?>