<?php
namespace MaspostAPI;

class ForgotPassword {
    public $body;
    private $date;
    public $translations;

    function __construct($data) {
        ForgotPassword::setBody($data['pmb'], $data['token'], $data['email']);
    }

    function setBody($pmb, $token, $email) {
        $this->body = file_get_contents(__DIR__.'/foundations/forgot_password.html');
        $this->body = str_replace('{PMB}', $pmb, $this->body);
        $this->body = str_replace('{TOKEN}', $token, $this->body);
        $this->body = str_replace('{EMAIL}', $email, $this->body);
    }

    function getBody() {
        return $this->body;
    }
}
