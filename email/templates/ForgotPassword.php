<?php
namespace MaspostAPI;

class ForgotPassword {
    public $body;
    private $title;
    private $previewText;
    private $pmb;
    private $token;
    private $email;
    private $content;

    public $translations;

    function __construct($data) {
        $this->title = '<h1 style="padding-top: 20px;">Reestablecer Contraseña</h1>';
        $this->previewText = 'Olvidaste tu contraseña?';
        $this->pmb = $data['pmb'];
        $this->token =$data['token'];
        $this->email = $data['email'];

        ForgotPassword::setContent();
        ForgotPassword::setBody();
    }

    function setBody() {
        $this->body = file_get_contents(__DIR__.'/foundations/simple.html');
        $this->body = str_replace('{CONTENT}', $this->content, $this->body);
        $this->body = str_replace('{PREVIEW_TEXT}', $this->previewText, $this->body);
        $this->body = str_replace('{TITLE}', $this->title, $this->body);
        $this->body = str_replace('{TABLE}', '', $this->body);
    }

    function getBody() {
        return $this->body;
    }

    function setContent() {
        $this->content = '
                    <p style="padding-bottom: 20px;">Olvidaste tu contraseña? No hay problema! Solo haz click aquí para crear una nueva contraseña.<p>
                    <div style="padding: 5px 20px 50px; text-align: center;">
                    <a style="padding: 12px 14px; margin-bottom: 100px; background-color: #1c51c6; text-decoration: none;color: white;" href="http://users.maspostwarehouse.com/reset-password?token='.$this->token.'&email='.$this->email.'&pmb='.$this->pmb.'">Crea una nueva contraseña</a></div>';
    }
}
