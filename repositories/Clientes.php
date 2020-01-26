<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../database.php');
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
        $query = 'SELECT clientes.id, password, nombre, app, apm, clientes.pmb, direccion, estado, ciudad, pais, cp, clientes.email, vigencia, id_plan  as tipo_plan, credito, perfil_status FROM clientes LEFT JOIN c_recibir ON clientes.pmb =  c_recibir.pmb where clientes.pmb = '.$pmb.' AND c_recibir.tipo = 1;';

        $result = $db->query($query);

        if (!empty($result)) {
            $row = $result->fetch();
            return $row;
        }
    }

    public static function updateClienteById($dataToUpdate, $id)
    {
        $db = new DB();
        $db = $db->getConnection();
        $columnsToUpdate = '';

        foreach ($dataToUpdate as $key => $data) {
            $columnsToUpdate .= $key.'="'.$data.'",';
        }
        $query = 'UPDATE clientes SET '.substr($columnsToUpdate, 0, -1).' WHERE id ='.$id;

        $insertPassword = $db->prepare($query);
        $insertPassword->execute();

        if ($insertPassword->rowCount() > 0) {
            return true;
        }

        return false;
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

    public static function getAllAutorizadosRecibir()
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

    public static function getAllAutorizadosEntregar()
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

    public static function getAccountBalance()
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
