<?php
namespace MaspostAPI;

use PHPMailer\PHPMailer;

class Email {

    protected $mail;
    private $recipient;

    function __construct($email, $subject, $template, $hasCC = false, $ccEmail = 'info@maspostwarehouse.com') {
        $this->recipient = $email;
        $this->mail = new PHPMailer\PHPMailer();
        $this->mail->isSMTP();
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Host = 'maspostwarehouse.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'noreply@maspostwarehouse.com';
        $this->mail->Password = 'Bendecida77';
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->From = 'noreply@maspostwarehouse.com';
        $this->mail->Sender = 'noreply@maspostwarehouse.com';
        $this->mail->Port = 465;
        $this->mail->isHTML(true);

        $this->mail->setFrom('noreply@maspostwarehouse.com', 'Maspost Warehouse');
        $this->mail->addReplyTo('noreply@maspostwarehouse.com', 'Maspost Warehouse');
        $this->mail->addAddress($this->recipient);

        if ($hasCC && !empty($ccEmail)) {
            $this->mail->addCC($ccEmail);
        }

        $this->mail->Subject = $subject;
        $this->mail->MsgHTML($template);
    }

    function send() {
        return $this->mail->send();
    }

    function getErrorInfo() {
        return $this->mail->ErrorInfo;
    }
}
