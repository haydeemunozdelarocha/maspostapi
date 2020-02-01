<?php
namespace MaspostAPI\Routes\Auth;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Auth.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Auth;
use MaspostAPI\Repositories\Clientes;
use Slim\Http\Request;
use Slim\Http\Response;

class RESET_PASSWORD extends ENDPOINT
{
    private $data = [];
    private $type;

    protected function check_2_body(Request $request, Response $response, array &$args)
    {
        $parsedBody = $request->getParsedBody();

        $this->data = [];

        if (!$parsedBody||
            !isset($parsedBody['password']) ||
            empty($parsedBody['password']) ||
            !isset($parsedBody['token']) ||
            empty($parsedBody['token']) ||
            !isset($parsedBody['email']) ||
            empty($parsedBody['email']) ||
            !isset($parsedBody['pmb']) ||
            empty($parsedBody['pmb'])) {

            return $response->withStatus(400)->withJson('Please enter a valid password');
        } else {
            $this->data['password'] = $parsedBody['password'];
            $this->data['email'] = $parsedBody['email'];
            $this->data['token'] = $parsedBody['token'];
            $this->data['pmb'] = $parsedBody['pmb'];

            return true;

        }
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {

        $credentials = Auth::getCustomerCredentials($this->data['email']);

        if(!empty($credentials) && $this->data['pmb'] == $credentials[0]['pmb'])
        {

            if(password_verify($this->data['token'], $credentials[0]['password']))
            {
                if(Auth::setPassword($credentials[0]['id'], $this->data['password']))
                {
                    $user = Clientes::getClientInfo($this->data['pmb']);
                    unset($user['password']);
                    if ($user) {
                        return $response->withJson($user, 200);
                    }
                } else {
                    return $response->withStatus(400)->withJson('Wrong customer id.');
                }
            } else
            {
                return $response->withStatus(400)->withJson('This token has expired. Please request a new token to be sent via email.');
            }

            return $response->withStatus(400);
        }
    }
}
