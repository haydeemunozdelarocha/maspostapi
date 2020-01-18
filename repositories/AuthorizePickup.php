<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../new_database.php');
use DB;

class AuthorizePickup
{

    public static function create($ids, $authorizedName)
    {
        $db = new DB();
        $db = $db->getConnection();
        date_default_timezone_set('America/Denver');
        $date = date("y-m-d");
        $ids = implode(",", $ids);

        $insertAuthorizedMessage = $db->prepare("INSERT INTO mensajes(fecha, mensaje) VALUES(?, ?); SET @mensaje :=(SELECT mensajes.id FROM mensajes ORDER BY id DESC LIMIT 1); update recepcion SET mensaje =@mensaje WHERE recepcion.id IN (?);");
        $insertAuthorizedMessage->execute(array($date, $authorizedName, $ids));

        if ($insertAuthorizedMessage->rowCount() > 0) {
            return true;
        }
    }
}
