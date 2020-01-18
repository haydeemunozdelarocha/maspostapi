<?php
namespace MaspostAPI\Routes\Auth;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Auth.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Auth;

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

        if (!empty($credentials)){
            $isPasswordValid = $isAdmin ? $parsedBody['password'] === $credentials[0]['password'] : password_verify($parsedBody['password'], $credentials[0]['password']);
            $formattedCredentials = [
                'id' => $credentials[0]['id'],
                'email' => $credentials[0]['email']
            ];
            $formattedCredentials['tipo'] = $isAdmin ? $credentials[0]['tipo'] : 'user';

            if ($isAdmin) {
                $formattedCredentials['nombre'] = $credentials[0]['nombre'];
            } else {
                $formattedCredentials['pmb'] = $credentials[0]['pmb'];
            }
        }

        if($isPasswordValid)
        {
            return $response->withStatus(200)->withJson($formattedCredentials);
        } else {
            return $response->withStatus(500)->withJson('Invalid credentials');
        }
    }
}
