<?php

require_once __DIR__ . '/../../assets/vendor/phpmailer/Exception.php';
require_once __DIR__ . '/../../assets/vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../../assets/vendor/phpmailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

function send_email($to, $subject, $body, $reply_to = null)
{
    $mail = new PHPMailer(true);

    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'alldyneltd@gmail.com';
    $mail->Password = 'uega vtka awtl nbpx';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //MUDAR PARA $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; QUANDO FOR HOSPEDAR
    $mail->Port = 587; //MUDAR PARA 587 QUANDO FOR HOSPEDAR

    $mail->setFrom('alldyneltd@gmail.com', 'All Dyne Ltd');
    $mail->addAddress($to);

    if($reply_to !== null) {
        $mail->addReplyTo($reply_to);
    }

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $body;

    $mail->AltBody = strip_tags($body);

    return $mail->send();

}
?>