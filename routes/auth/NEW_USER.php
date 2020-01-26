<?php
namespace MaspostAPI\Routes\Auth;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Auth.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

class NEW_USER extends ENDPOINT
{
    private $data = [];

    protected function check_2_body(Request $request, Response $response, array &$args)
    {
        $parsedBody = $request->getParsedBody();

        $this->data = [];

        if (!$parsedBody||
            !isset($parsedBody['password']) ||
            empty($parsedBody['password']) ||
            !isset($parsedBody['email']) ||
            empty($parsedBody['email']) ||
            !isset($parsedBody['pmb']) ||
            empty($parsedBody['pmb'])) {
            return $response->withStatus(400)->withJson([
                'message' => 'Invalid email, pmb or password'
            ]);
        } else {
            $this->data['password'] = $parsedBody['password'];
            $this->data['email'] = $parsedBody['email'];
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
        $user =  Auth::createUser($this->data);

        if (!empty($user)) {
            return $response->withStatus(200)->withJson($user);
        } else {
            return $response->withJson([
                'message' => 'PMB is either invalid or already registered with another email. Please login or reset your password.'
            ], 422);
        }
    }
}
