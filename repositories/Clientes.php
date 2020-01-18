<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../new_database.php');
use DB;

class Clientes
{
    public static function getPlanQuery($pmb)
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'SELECT(case when clientes.razon_social then 
clientes.razon_social else c_recibir.nombre end) AS nombre,planes.nombre AS plan,clientes.pmb,clientes.credito AS saldo 
FROM clientes JOIN c_recibir ON c_recibir.pmb = clientes.pmb JOIN planes ON clientes.id_plan = planes.id WHERE 
clientes.pmb = ' . $pmb . ' AND c_recibir.tipo=1 LIMIT 1;';
        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    public static function getClientInfo($pmb)
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'SELECT * FROM clientes where pmb = '.$pmb.' LIMIT 1;';

        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    public static function getAllRecibir()
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'SELECT pmb, TRIM(CONCAT(nombre," ", app, " ", apm)) as nombre FROM maspost.c_recibir order by CAST(pmb AS UNSIGNED);';

        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }
}
