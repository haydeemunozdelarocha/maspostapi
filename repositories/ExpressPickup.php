<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../database.php');
use DB;
require_once(__DIR__.'/../email/EmailHelpers.php');
require_once(__DIR__.'/../email/Email.php');

use MaspostAPI\Email;
use MaspostAPI\EmailHelpers;

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
            $recepcionQuery = 'SELECT recepcion.*, mensajes.mensaje as nombre_autorizado FROM recepcion LEFT JOIN mensajes ON mensajes.id_recepcion = recepcion.id WHERE recepcion.id IN (SELECT recepcion_id FROM maspost.express_recepcion where express_id='.$id.') LIMIT 1;';

            $getRecepcionIds = $db->query($recepcionQuery);

            if (!empty($getRecepcionIds)) {
                $data['paquetes'] = $getRecepcionIds->fetchAll();

                return $data;
            }

        }
    }

    public static function create($ids, $pmb, $fecha, $hora, $name)
    {
        $db = new DB();
        $db = $db->getConnection();
        $formattedDate = $fecha . " " . $hora;
        $insertEntregaExpress = $db->prepare("INSERT INTO maspost.entrega_express(fecha, confirmado) VALUES(?, ?)");
        $insertEntregaExpress->execute(array($formattedDate, 0));

        if ($insertEntregaExpress->rowCount() > 0) {
            $express_id = $db->lastInsertId();

            foreach ($ids as $id) {
                if ($name) {
                    AuthorizePickup::create($id, $name);
                }

                $insertEntregaId = $db->prepare("INSERT INTO express_recepcion(recepcion_id, express_id) VALUES(?, ?)");
                $insertEntregaId->execute(array($id, $express_id));

                if ($insertEntregaId->rowCount() > 0) {
                    $newExpressPickup = ExpressPickup::getOne($express_id);
                    $templateData = [
                        'pmb' => $pmb,
                        'date' => $newExpressPickup['fecha'],
                        'ids' => $ids,
                        'packages' => $newExpressPickup['paquetes']
                    ];

                    $email = Clientes::getClientInfo($pmb)['email'];
                    $emailUser = new Email($email, EmailHelpers::getSubject('entrega_express', $templateData), EmailHelpers::getTemplate($templateData, 'entrega_express'));

                    if(!$emailUser->send())
                    {
                        echo 'User email could not be sent.';
                        echo 'Mailer Error: ' . $emailUser->getErrorInfo();
                    } else {
                        $adminMail = new Email('haydee.mr0@hotmail.com', EmailHelpers::getSubject('entrega_express_admin', $templateData), EmailHelpers::getTemplate($templateData, 'entrega_express_admin'));

                        if(!$adminMail->send())
                        {
                            echo 'User email could not be sent.';
                            echo 'Mailer Error: ' . $emailUser->getErrorInfo();
                        } else {
                            return $newExpressPickup;
                        }
                    }
                }
            }
        }

        return false;
    }
}
