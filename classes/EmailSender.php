<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailSender
{
    public function contactFormSend()
    {
        //Load Composer's autoloader
        require 'vendor/autoload.php';

        date_default_timezone_set('Europe/Copenhagen');

        // admin@dating.virtusb.com, kode: rootpwdating13

        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        $mail->CharSet = 'UTF-8';
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
            $mail->addAddress('admin@dating.virtusb.com'); // Add a recipient
            $mail->addReplyTo('admin@dating.virtusb.com', 'TEC DatingAp');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
        return $_SERVER['HTTP_CLIENT_IP'];
    }
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}

            //Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Besked fra besøgende på dating.virtusb.com';
            $mail->Body = "<p style='font-size: 30px;'><strong>Besked:</strong></p> " . $_POST['contact-message'] .

              "<p style='font-size: 30px;'><strong>Afsender navn:</strong></p> " . $_POST['contact-firstname'] . " " . $_POST['contact-lastname'] .

              "<p style='font-size: 30px;'><strong>Afsender email:</strong></p> " . $_POST['contact-email'] .

              "<p style='font-size: 30px;'><strong>Afsender tlf. nr:</strong></p> " . $_POST['contact-phone'] .

              //"<p style='font-size: 30px;'><strong>Foretrækker at blive kontaktet om:</strong></p> " . $_POST['contact-preference'] .

              "<p style='font-size: 30px;'><strong>IP adresse:</strong></p> " . getUserIpAddr();
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            return true;
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

    }

    public function sendWelcomeEmail()
    {

        //Load Composer's autoloader
        require 'vendor/autoload.php';

        date_default_timezone_set('Europe/Copenhagen');

        // admin@dating.virtusb.com, kode: rootpwdating13

        $mail = new PHPMailer(true); // Passing `true` enables exceptions
        $mail->CharSet = 'UTF-8';
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
            $mail->Body = "<p style='font-size: 30px;'>Velkommen, " . ucfirst(strtolower(strtok($_POST['name'], " "))) . "</p>" .
            "<br><p style='font-size: 30px;'>Her er dine kontodetaljer</p>" .
            "<p>Fulde navn: " . ucfirst(strtolower($_POST['name'])) . "</p>" .
                "<p>Email: " . $_POST['email'] . "</p>" .
                "<p>Brugernavn: " . $_POST['username'] . "</p>" .
                "<p>Adgangskode: " . $_POST['password'] . "</p>" .
                "<br><br><p style='font-size: 24px;'>Vi håber du møder den du søger!</p>" .
                "<br><p>&copy; 2018 Dating App</p>";
            //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    }
}
