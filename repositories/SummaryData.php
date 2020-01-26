<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../database.php');
use DB;
use PDO;

class SummaryData
{
    /**
     * Get Type per Customer
     *
     * @param $startDate
     * * @param $endDate

     * @return String
     */
    public static function getInventoryTypeByCustomer($data)
    {
        $db = new DB();
        $db = $db->getConnection();
//        $query = 'select tipo_entradas.nombre as tipo,recepcion.pmb,recepcion.nombre, format(SUM(case when format(salidas.total,2) then format(salidas.total,2) else 0 end), 2) as total, count(*) as qty from recepcion inner join tipo_entradas on recepcion.tipo=tipo_entradas.id inner join fleteras on fleteras.id=recepcion.fletera  inner join usuarios on usuarios.id=recepcion.id_empleado_recibe inner join clientes on clientes.pmb=recepcion.pmb inner join planes on clientes.id_plan=planes.id inner join salidas on recepcion.id_salida=salidas.id where year(fecha_recepcion)="2019" AND salidas.credito="1"  group by pmb, tipo order by pmb, nombre, tipo;';
        $query = 'SELECT nombre FROM maspost.tipo_entradas';
        $result = $db->query($query);

        $types = $result->fetchAll(PDO::FETCH_COLUMN, 0);
        $secondQuery = "SELECT r.pmb, TRIM(r.nombre) as cliente,";

        foreach ($types as $index => $type) {
            $secondQuery .= "SUM(CASE WHEN t.`nombre` = '".$type."' then 1 else 0 end) as '" . $type ."'";
            if($index + 1 !== sizeof($types))
            {
                $secondQuery .= ", ";
            }
        }
        $secondQuery .= " from recepcion r left join tipo_entradas t on r.tipo = t.id WHERE r.fecha_recepcion BETWEEN STR_TO_DATE('".$data['startDate']."', '%Y-%m-%d') AND STR_TO_DATE('".$data['endDate']."', '%Y-%m-%d') group by r.pmb, r.nombre order by pmb desc;";

      // return $secondQuery;
        $result = $db->query($secondQuery);
        $inventoryTypeByCustomer = $result->fetchAll();
        return $inventoryTypeByCustomer;
    }

    public static function getSummary($type, $data)
    {
        switch ($type) {
            case "inventorytype_customer":
                $summary = SummaryData::getInventoryTypeByCustomer($data);
                return $summary;
                break;
        }
    }
}
