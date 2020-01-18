<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../new_database.php');
use DB;

class Recepcion
{
    /**
     * Get All Recepcion
     *
     * @param $pmb
     * @param $status
     * @param $date
     *
     * @return Array
     */
    public static function getAllRecepcionQuery($pmb, $status = 'en_bodega', $date = '')
    {
        $db = new DB();
        $db = $db->getConnection();
        $dateQuery = '';
        $dateArray = explode("/", $date);

        if (!empty($dateArray[0]) && !empty($dateArray[1])) {
            $dateQuery = 'AND YEAR(fecha_entrega) = '.$dateArray[1].' AND MONTH(fecha_entrega) = '. $dateArray[0]. ' ';
        }

        $statusQuery = $status === 'en_bodega' ? 'where fecha_entrega IS NULL AND id_salida = 0 ' : 'where fecha_entrega IS NOT NULL ';

        $deliveryColumn = '';
        $pickupPerson = '';
        $joinPickupTable = '';

        if ($status === 'entregado') {
            $deliveryColumn = 'recepcion.id_salida as `# Salida`,';
            $pickupPerson = ',salidas.entrego as `Recibió`';
            $joinPickupTable = 'join salidas on recepcion.id_salida = salidas.id ';
        }

        $columns = self::formatDeliveryDate().', recepcion.entrada as ID,'.$deliveryColumn.' tipo_entradas.nombre AS tipo, recepcion.fromm as `remitente`, recepcion.nombre as `destinatario`,fleteras.nombre AS fletera, '. self::truncatedTrackingNumber().'  as `tracking`,recepcion.peso as `peso(lbs)`, CONCAT("$", recepcion.cod) as `COD`'.$pickupPerson;
        $query = 'select '.$columns.'from recepcion join tipo_entradas on recepcion.tipo = tipo_entradas.id join fleteras on recepcion.fletera = fleteras.id '.$joinPickupTable.$statusQuery.$dateQuery.' AND recepcion.pmb = ' . $pmb . ' ORDER BY recepcion.fecha_recepcion DESC';
        $result = $db->query($query);
        $data = [];

        if (!empty($result)) {
            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    /**
     * Get One Recepcion
     *
     * @param $pmb
     *
     * @return Array
     */
    public static function getOneRecepcionQuery($pmb, $id)
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'select (concat(DATE_FORMAT(recepcion.fecha_recepcion,"%d"),"-",ELT(DATE_FORMAT(recepcion.fecha_recepcion,"%m"),"Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"),"-",DATE_FORMAT(recepcion.fecha_recepcion,"%y"))) AS fecha_recepcion,recepcion.entrada,recepcion.id,recepcion.peso,tipo_entradas.nombre AS tipo,recepcion.fromm,recepcion.nombre,fleteras.nombre AS fletera,recepcion.traking,recepcion.cod  from recepcion join tipo_entradas on recepcion.tipo = tipo_entradas.id join fleteras on recepcion.fletera = fleteras.id where fecha_entrega IS NULL AND id_salida =0 AND pmb = ' . $pmb . ' ORDER BY recepcion.fecha_recepcion DESC';
        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    public static function getGroupRecepcionQuery($ids, $pmb)
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'select recepcion.entrada,recepcion.nombre,(concat(DATE_FORMAT(recepcion.fecha_recepcion,"%d"),"-",ELT(DATE_FORMAT(recepcion.fecha_recepcion,"%m"),"Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"),"-",DATE_FORMAT(recepcion.fecha_recepcion,"%y"))) as fecha_recepcion, mensajes.mensaje from recepcion LEFT JOIN mensajes ON recepcion.mensaje = mensajes.id where recepcion.id in (' . $ids . ')';
        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    public static function getFleteras()
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'select id, nombre FROM fleteras;';
        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    public static function formatDeliveryDate() {
        return '(concat(DATE_FORMAT(recepcion.fecha_recepcion,"%d"),"-",ELT(DATE_FORMAT(recepcion.fecha_recepcion,"%m"),"Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"),"-",DATE_FORMAT(recepcion.fecha_recepcion,"%y"))) AS fecha_recepcion';
    }

    public static function truncatedTrackingNumber() {
        return 'CONCAT(SUBSTRING(recepcion.traking, 1, 4), "...", SUBSTRING(recepcion.traking, LENGTH(recepcion.traking) - 3))';
    }
}


