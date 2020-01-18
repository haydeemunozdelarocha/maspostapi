<?php
namespace MaspostAPI;
require_once(__DIR__.'/templates/ExpressPickup.php');
require_once(__DIR__.'/templates/ForgotPassword.php');

class EmailHelpers {
    function __construct() {

    }
    static function getTemplate($data, $type) {
        switch ($type) {
            case "entrega_express":
                $template = new Template\ExpressPickup($data);
                return $template->getBody();
                break;
            case "forgot_password":
                $template = new Template\ForgotPassword($data);
                return $template->getBody();
                break;
            case "Kuchen":
                echo "i ist Kuchen";
                break;
        }
    }

    static function getSubject($type) {
        switch ($type) {
            case "entrega_express":
                return "Nueva Entrega Express";
                break;
            case "forgot_password":
                return "Reestablecer Contraseña";
                break;
            case "Balken":
                echo "i ist Balken";
                break;
            case "Kuchen":
                echo "i ist Kuchen";
                break;
        }
    }
}
