<?php
	/**
	 * This example shows settings to use when sending via Google's Gmail servers.
	 */
	//SMTP needs accurate times, and the PHP time zone MUST be set
	//This should be done in your php.ini, but this is how to do it if you don't have access to that
	require 'functions.php';
	$user = $_POST["fp_username"];
	$email = query("SELECT Name, Email FROM Staff WHERE Username='$user'");
	// print_r($email);
	if(empty($email[0]["Email"]))
		echo json_encode("false");
	else
	{
		date_default_timezone_set('Etc/UTC');

		$newpassword = bin2hex(openssl_random_pseudo_bytes(5, $cstrong));
		// echo($newpassword);
		$dbpass = crypt($newpassword);

		$email_id = "";
		$password = "";

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
		$mail->Username = $email_id;
		//Password to use for SMTP authentication
		$mail->Password = $password;
		//Set who the message is to be sent from
		$mail->setFrom($email_id, 'VESIT Attendance');
		//Set an alternative reply-to address
		// $mail->addReplyTo('replyto@example.com', 'First Last');
		//Set who the message is to be sent to
		$mail->addAddress($email[0]["Email"], $email[0]["Name"]);
		//Set the subject line
		$mail->Subject = 'Password Reset - Attendance';
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		// $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		//Replace the plain text body with one created manually
		$mail->Body = 'Your new Password is '.$newpassword;
		//Attach an image file
		// $mail->addAttachment('images/phpmailer_mini.png');
		//send the message, check for errors
		if (!$mail->send()) {
			echo json_encode("0");
		} else {
			query("UPDATE Staff SET Salt = '$dbpass' WHERE Username='$user'");
			echo json_encode("1");
		}
	}
?>