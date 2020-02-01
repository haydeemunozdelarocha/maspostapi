<?php
namespace MaspostAPI\Routes\Auth;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Auth.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Auth;
use MaspostAPI\Repositories\Clientes;

use Slim\Http\Request;
use Slim\Http\Response;

class LOGIN extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $parsedBody = $request->getParsedBody();

        if (!$parsedBody ||
            (!isset($parsedBody['email']) ||
                empty($parsedBody['email']) ||
                !isset($parsedBody['password']) ||
                empty($parsedBody['password']))) {

            return $response->withStatus(400)->withJson('Please enter an email and password');
        }

        $isAdmin = $parsedBody['isAdmin'] || false;

        $credentials = $isAdmin ? Auth::getAdminCredentials($parsedBody['email']) : Auth::getCustomerCredentials($parsedBody['email']);

        $isPasswordValid = false;
        $user = null;

        if (!empty($credentials)){
            $isPasswordValid = $isAdmin ? $parsedBody['password'] === $credentials['password'] : password_verify($parsedBody['password'], $credentials['password']);
            if ($isAdmin) {
                $user = $credentials;
            } else {
                $user = Clientes::getClientInfo($credentials['pmb']);
                $user['tipo'] = 'user';
            }

            unset($user['password']);
        }

        if($isPasswordValid && $user)
        {
            return $response->withStatus(200)->withJson($user);
        } else {
            return $response->withStatus(500)->withJson('Invalid credentials');
        }
    }
}
