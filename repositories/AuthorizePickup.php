<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../database.php');
use DB;

class AuthorizePickup
{
    public static function create($id, $authorizedName)
    {
        $db = new DB();
        $db = $db->getConnection();
        date_default_timezone_set('America/Denver');
        $date = date("y-m-d");
        $insertAuthorizedMessage = $db->prepare("INSERT INTO mensajes(fecha, mensaje, id_recepcion) VALUES(?, ?, ?); SET @mensaje :=(SELECT mensajes.id FROM mensajes ORDER BY id DESC LIMIT 1); update recepcion SET mensaje =@mensaje WHERE recepcion.id = ?;");
        $insertAuthorizedMessage->execute(array($date, $authorizedName, $id, $id));

        if ($insertAuthorizedMessage->rowCount() > 0) {
            return $db->lastInsertId();
        }

        return false;
    }

    public static function bulkCreate($ids, $authorizedName)
    {
        $db = new DB();
        $db = $db->getConnection();
        $authorizedNameSet = array_filter($ids, function($id) use ($authorizedName) {
            return self::create($id, $authorizedName);
        });

        if (sizeof($ids) === sizeof($authorizedNameSet)) {
            $idsString = implode(',', $ids);
            $recepcionQuery = 'SELECT recepcion.*, mensajes.mensaje as nombre_autorizado FROM recepcion JOIN mensajes ON mensajes.id_recepcion = recepcion.id WHERE recepcion.id IN ('.$idsString.') AND mensajes.mensaje = '.$authorizedName.' LIMIT 1;';
            $packagesResult = $db->prepare($recepcionQuery);
            $packagesResult->execute();

            if ($packagesResult) {
                return $packagesResult->fetchAll();

            }
        }

        return false;
    }

    public static function addToAutorizadoHistory($pmb, $authorizedName){
        $autorizadosOficialesEntrega = Clientes::getAllAutorizadosEntregar($pmb);

        $existingNames = array_filter($autorizadosOficialesEntrega, function($autorizado) use ($authorizedName) {
            return $autorizado['nombre'] === $authorizedName;
        });

        if (sizeof($existingNames) > 0) {
            return true;
        } else {
          // TODO: add authorized history

        }
    }
}
