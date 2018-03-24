<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    public function sendWelcomeEmail()
    {

        //Load Composer's autoloader
        require 'vendor/autoload.php';

        date_default_timezone_set('Europe/Copenhagen');

        // admin@dating.virtusb.com, kode: rootpwdating13

        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 0; // Enable verbose debug output
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'de9.fcomet.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'admin@dating.virtusb.com'; // SMTP username
            $mail->Password = 'rootpwdating13'; // SMTP password
            $mail->SMTPSecure = 'ssl'; //false;                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465; //587; //465;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('admin@dating.virtusb.com', 'TEC DatingApp');
            $mail->addAddress($_POST['email'], $_POST['name']); // Add a recipient
            $mail->addReplyTo('admin@dating.virtusb.com', 'TEC DatingAp');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Din konto hos DatingApp er blevet oprettet';
            $mail->Body = 'Velkommen, ' . strtok($_POST['name'], " ");
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
