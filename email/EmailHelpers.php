<?php
namespace MaspostAPI;
require_once(__DIR__.'/templates/ExpressPickup.php');
require_once(__DIR__.'/templates/AuthorizedName.php');
require_once(__DIR__.'/templates/ForgotPassword.php');
require_once(__DIR__.'/../helpers/Helpers.php');

use MaspostAPI\Helpers\Date;

class EmailHelpers {
    function __construct() {

    }
    static function getTemplate($data, $type) {
        switch ($type) {
            case "entrega_express":
                $template = new ExpressPickup($data);
                return $template->getBody();
                break;
            case "entrega_express_admin":
                $isAdmin = true;
                $template = new ExpressPickup($data, $isAdmin);
                return $template->getBody();
                break;
            case "autorizado":
                $template = new AuthorizedName($data);
                return $template->getBody();
                break;
            case "forgot_password":
                $template = new ForgotPassword($data);
                return $template->getBody();
                break;
            case "Kuchen":
                echo "i ist Kuchen";
                break;
        }
    }

    static function getSubject($type, $data = []) {
        switch ($type) {
            case "entrega_express":
                if (Date::isWeekend($data['date'])) {
                    return "#".$data['pmb']." - Fin de Semana: Nueva Entrega Express";
                }

                return "Nueva Entrega Express";
            case "entrega_express_admin":
                if (Date::isWeekend($data['date'])) {
                    return "#".$data['pmb']." - Confirmar: Nueva Entrega Express Fin de Semana";
                }
                return "#".$data['pmb']." - Nueva Entrega Express";
                break;
            case "forgot_password":
                return "Reestablecer Contraseña";
                break;
            case "autorizado":
                return "#".$data['pmb']." - Nueva Autorización de Entrega";
                break;
            case "Kuchen":
                echo "i ist Kuchen";
                break;
        }
    }
}
