<?php
namespace MaspostAPI;

use PHPMailer\PHPMailer;

class Email {

    protected $mail;
    private $recipient;

    function __construct($email, $subject, $template) {
        $this->recipient = $email;
        $this->mail = new PHPMailer\PHPMailer();
        $this->mail->isSMTP();
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Host = 'a2plcpnl0769.prod.iad2.secureserver.net';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'noreply@maspostwarehouseusers.com';
        $this->mail->Password = 'Bendecida77';
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->From = 'noreply@maspostwarehouseusers.com';
        $this->mail->Sender = 'noreply@maspostwarehouseusers.com';
        $this->mail->Port = 465;
        $this->mail->isHTML(true);

        $this->mail->setFrom('noreply@maspostwarehouseusers.com', 'Maspost Warehouse');
        $this->mail->addReplyTo('noreply@maspostwarehouseusers.com', 'Maspost Warehouse');
        $this->mail->addAddress($this->recipient);

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
