<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp-mail.outlook.com';                // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'yib00001sk@aspire2student.ac.nz';  // SMTP username
$mail->Password = 'Ron971nt';                         // SMTP password
$mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->From='yib00001sk@aspire2student.ac.nz';
$mail->FromName=$the_sender;
$mail->addAddress($the_tomail);                       // Add a recipient
$mail->addCC($the_cc);
/*$mail->addAddress('ellen@example.com');             // Name is optional
$mail->addReplyTo('info@example.com', 'Information');
$mail->addBCC('bcc@example.com');

$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name */
$mail->isHTML(true);                                  // Set email format to HTML 

//$the_message = "Car Launcher message";
$mail->Message = $the_message;
$mail->Subject = $the_subject;
//$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->Body    = $the_message;
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

$error = "";
if(!$mail->send()) {
    $error = 'Mail sending error: ' . $mail->ErrorInfo;
} else {
    $error = "";
}