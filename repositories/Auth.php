<?php
namespace MaspostAPI\Repositories;
require_once(__DIR__.'/../database.php');
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

    public static function acceptTerms($id, $pmb)
    {
        $db = new DB();
        $db = $db->getConnection();

        $updateProfileStatus = $db->prepare('UPDATE clientes SET perfil_status = 2 WHERE id=?');
        $updateProfileStatus->execute(array($id));

        $updatedUser = Clientes::getClientInfo($pmb);

        if ($updatedUser) {
            return $updatedUser;
        }

        return false;
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

    public static function createUser($userData)
    {
        $customerData = Clientes::getClientInfo($userData['pmb']);

        if (!empty($customerData)) {
            // User exists and password is correct, Log in
            if ($userData['email'] === $customerData['email'] &&
                password_verify($userData['password'], $customerData['password'])) {
                return $customerData;
            }

            // User exists but no email is set
            if (empty($customerData['email'])) {
                $hashPassword = password_hash($userData['password'], PASSWORD_BCRYPT, [
                    'cost' => 11
                ]);
                $dataToUpdate = [
                   'email' => $userData['email'],
                   'password' => $hashPassword,
                    'perfil_status' => 1
                ];

                $updatedUser = Clientes::updateClienteById($dataToUpdate, $customerData['id']);

                if ($updatedUser) {
                    $userInfo = Clientes::getClientInfo($userData['pmb']);
                    unset($userInfo['password']);
                    return $userInfo;
                }
            }

            // User exists but email or password is wrong
            if ($userData['email'] !== $customerData['email']) {
                return;
            }
        }

        return;
    }
}
