<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    public static function mail($to, $subject, $body) {
        $mail = new PHPMailer();
        $mail->CharSet = "UTF-8";
        $mail->isSMTP();
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "smtp.eu.mailgun.org";
        $mail->Port = 587; // or 587
        $mail->IsHTML(true);
        $mail->Username = "ibu@mg.adnan.dev";
        $mail->Password = "8c33fbaf38dc4c031e1c6e353f8e560b-52b0ea77-bc658118";
        $mail->SetFrom("pbleague2018@gmail.com");
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        /* send activation email */
        if(!$mail->Send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
         } else {
            return "Email has been sent!";
         }
    }
}
?> 