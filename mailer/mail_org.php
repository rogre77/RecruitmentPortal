<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

$the_sender = "Car Launcher Administrator";
$the_subject = "Car Launcher password reset link";
$the_message = "This is the password reset link: http://link.com";

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp-mail.outlook.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'yib00001sk@aspire2student.ac.nz';                 // SMTP username
$mail->Password = 'Ron971nt';                           // SMTP password
$mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->From='yib00001sk@aspire2student.ac.nz';
$mail->FromName=$the_sender;
$mail->addAddress('rogre77@gmail.com', 'Roger');     // Add a recipient
/*$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('info@example.com', 'Information');
$mail->addCC('cc@example.com');
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

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    //echo 'Message has been sent';
}