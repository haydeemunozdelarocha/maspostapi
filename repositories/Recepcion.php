<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../database.php');
use DB;

class Recepcion
{
    /**
     * Get All Recepcion
     *
     * @param $queryData Array
     *
     * @return Array
     */
    public static function getAllRecepcionQuery($queryData)
    {
        $db = new DB();
        $db = $db->getConnection();
        $dateQuery = '';
        $deliveryColumn = '';
        $pickupPerson = '';
        $joinPickupTable = '';
        $pickUpDateColumn = '';
        $orderByDate= '';

        if (isset($queryData['status']) && $queryData['status'] === 'en_bodega') {
            $statusQuery = 'where fecha_entrega IS NULL AND id_salida = 0 ';
            $orderByDate = 'recepcion.fecha_recepcion DESC';
        } else {
            if (isset($queryData['month']) || isset($queryData['year'])) {
                if (!empty($queryData['month'])) {
                    $dateQuery .= ' AND DATE_FORMAT(fecha_entrega, "%m") = '. $queryData['month']. ' ';
                }
//
                if (!empty($queryData['year'])) {
                    $dateQuery .= 'AND DATE_FORMAT(fecha_entrega, "%Y") = '.$queryData['year'].' ';
                }
            } else {
                $deliveryColumn = 'recepcion.id_salida as `# Salida`,';
                $pickUpDateColumn = ' '.self::formatDate('fecha_entrega', 'fecha_entrega').',';
                $dateQuery = ' AND DATE_FORMAT(fecha_entrega, "%Y-%m-%d %H:%i:%s") BETWEEN DATE_ADD(NOW(), INTERVAL -11 MONTH) AND NOW() ';
            }

            $pickupPerson = ',salidas.entrego as `Recibió`';
            $orderByDate = 'recepcion.fecha_entrega DESC';
            $statusQuery = 'where fecha_entrega IS NOT NULL AND id_salida IS NOT NULL ';
        }

        $columns = self::formatDate('recepcion.fecha_recepcion', 'fecha_recepcion').', recepcion.id as ID, recepcion.entrada as Entrada,'.$deliveryColumn.$pickUpDateColumn.' tipo_entradas.nombre AS tipo, recepcion.fromm as `remitente`, recepcion.nombre as `destinatario`,fleteras.nombre AS fletera, '. self::truncatedTrackingNumber().'  as `tracking`,recepcion.peso as `peso(lbs)`, CONCAT("$", recepcion.cod) as `COD`'.$pickupPerson;
        $query = 'select '.$columns.'from recepcion join tipo_entradas on recepcion.tipo = tipo_entradas.id join fleteras on recepcion.fletera = fleteras.id left join salidas on recepcion.id_salida = salidas.id '.$statusQuery.$dateQuery.' AND recepcion.pmb = ' . $queryData['pmb'] . ' ORDER BY '.$orderByDate;
        //return json_encode($query);

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

    public static function updateGroup($ids, $dataToUpdate)
    {
        if (!empty($ids) && !empty($dataToUpdate)) {
            $updatedPackages = array_filter($ids, function($id, $dataToUpdate) {
                return self::updatePackageById($dataToUpdate, $id);
            });

            if (sizeof($updatedPackages) === sizeof($ids)) {
                return true;
            }
        }

        return false;
    }

    public static function updatePackageById($dataToUpdate, $id)
    {
        $db = new DB();
        $db = $db->getConnection();
        $columnsToUpdate = '';

        foreach ($dataToUpdate as $key => $data) {
            $columnsToUpdate .= $key.'="'.$data.'",';
        }
        $query = 'UPDATE recepcion SET '.substr($columnsToUpdate, 0, -1).' WHERE id ='.$id;

        $updatePackage = $db->prepare($query);
        $updatePackage->execute();

        if ($updatePackage->rowCount() > 0) {
            return true;
        }

        return false;
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

    public static function formatDate($date, $name) {
        return '(concat(DATE_FORMAT('.$date.',"%d"),"-",ELT(DATE_FORMAT('.$date.',"%m"),"Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"),"-",DATE_FORMAT('.$date.',"%y"))) AS '.$name;
    }

    public static function truncatedTrackingNumber() {
        return 'CONCAT(SUBSTRING(recepcion.traking, 1, 4), "...", SUBSTRING(recepcion.traking, LENGTH(recepcion.traking) - 3))';
    }
}


