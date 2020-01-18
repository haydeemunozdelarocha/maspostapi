<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../new_database.php');
use DB;

class Auth
{
    public static function getCustomerCredentials($email)
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'SELECT password, id, pmb, email FROM clientes where email = "'.$email.'" LIMIT 1;';
        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    public static function getAdminCredentials($email)
    {
        $db = new DB();
        $db = $db->getConnection();
        $query = 'SELECT pass as `password`, id, tipo, email, nombre FROM usuarios where email = "'.$email.'" LIMIT 1;';
        $result = $db->query($query);

        if (!empty($result)) {
            $data = [];

            while ($row = $result->fetchAll()) {
                $data = $row;
            }
            return $data;
        }
    }

    public static function setTemporaryPassword($id)
    {
        $token = uniqid(true);

        if(self::setPassword($id, $token))
        {
            return $token;
        }

        return false;
    }

    public static function setPassword($id, $password)
    {
        $db = new DB();
        $db = $db->getConnection();
        $hashPassword = password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 11
        ]);
        $insertPassword = $db->prepare('UPDATE clientes SET password = ? WHERE id = ?');
        $insertPassword->execute(array($hashPassword, $id));

        if ($insertPassword->rowCount() > 0) {
            return true;
        }
    }
}
