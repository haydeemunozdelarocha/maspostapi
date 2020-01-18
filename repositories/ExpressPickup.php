<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../new_database.php');
use DB;

class ExpressPickup
{
    public static function getOne($id)
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'SELECT * FROM maspost.entrega_express where express_id='.$id.' LIMIT 1;';

        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetch()) {
                $data = $row;
            }
            $recepcionQuery = 'SELECT * FROM recepcion WHERE id IN (SELECT recepcion_id FROM maspost.express_recepcion where express_id='.$id.');';

            $getRecepcionIds = $db->query($recepcionQuery);

            if (!empty($getRecepcionIds)) {
                $data['paquetes'] = $getRecepcionIds->fetchAll();

                return $data;
            }

        }
    }

    public static function create($ids, $fecha, $hora)
    {
        $db = new DB();
        $db = $db->getConnection();
        $formattedDate = $fecha . " " . $hora;
        $insertEntregaExpress = $db->prepare("INSERT INTO maspost.entrega_express(fecha, confirmado) VALUES(?, ?)");
        $insertEntregaExpress->execute(array($formattedDate, 0));

        if ($insertEntregaExpress->rowCount() > 0) {

            $query = "INSERT INTO entrega_express(fecha, confirmado) VALUES('09-20-2019 9:20 AM', 0);";
            $db->query($query);
            $express_id = $db->lastInsertId();

            foreach ($ids as $id) {
                $insertEntregaId = $db->prepare("INSERT INTO express_recepcion(recepcion_id, express_id) VALUES(?, ?)");
                $insertEntregaId->execute(array($id, $express_id));

                if ($insertEntregaId->rowCount() > 0) {
                    return ExpressPickup::getOne($express_id);
                }
            }
        }
    }
}
