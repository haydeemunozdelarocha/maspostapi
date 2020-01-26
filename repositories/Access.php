<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../database.php');
use DB;

class Access
{

    public static function validatePackageInPmb($pmb, $ids)
    {
        $db = new DB();
        $db = $db->getConnection();
        $ids = implode(",", $ids);

        foreach ($ids as $id) {
            $packagesInPmb = $db->prepare("SELECT * FROM maspost.recepcion WHERE pmb = ? AND id = ?");
            $packagesInPmb->execute(array($pmb, $id));

            if ($packagesInPmb->rowCount() !== 1) {
                return false;
            }
        }

        return true;
    }
}
