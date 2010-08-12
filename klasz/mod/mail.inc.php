<?php
define('SMTP_HOST', 'mail.ngiap.com');
define('SMTP_PORT', 27);
define('SMTP_USERNAME', 'klasz-team+ngiap.com');
define('SMTP_PASSWORD', 'ktngiap');
define('KLASZ_FROM_NAME', 'Klasz Team');
define('KLASZ_FROM_EMAIL', 'klasz-team@ngiap.com');

require_once("mod/phpmailer/class.phpmailer.php");

function send_mail($recipients, $subject, $html_msg, $plain_msg="") {
	$mail = new PHPMailer;	
	$mail->IsSMTP();
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT;
	$mail->SMTPAuth = true;
	$mail->Username = SMTP_USERNAME;
	$mail->Password = SMTP_PASSWORD;	
	$mail->From = KLASZ_FROM_EMAIL;
	$mail->FromName = KLASZ_FROM_NAME;
	$mail->SMTPKeepAlive = true;
	$mail->AddReplyTo(KLASZ_FROM_EMAIL, KLASZ_FROM_NAME);
	$mail->IsHTML(true);
	$mail->Subject = $subject;
	$mail->Body = $html_msg;
	$mail->AltBody = $plain_msg;
	
	foreach ($recipients as $key=>$value) { // email=>name pair
		$mail->AddAddress($key, $value);
		$mail->Send();
		$mail->ClearAddresses();
	}
	$mail->SmtpClose();
	return true; // TODO: check which one failed	
}
?>