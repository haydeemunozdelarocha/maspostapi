<?php
namespace MaspostAPI\Routes\Auth;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Auth.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

class ACCEPT_TERMS extends ENDPOINT
{
    private $id = null;
    private $pmb = null;

    protected function check_2_body(Request $request, Response $response, array &$args)
    {
        $parsedBody = $request->getParsedBody();

        if (!$parsedBody||
            !isset($parsedBody['id']) ||
            empty($parsedBody['id']) ||
            !isset($parsedBody['pmb']) ||
            empty($parsedBody['pmb'])) {
            return $response->withStatus(400)->withJson([
                'message' => 'Invalid id or PMB'
            ]);
        } else {
            $this->id = $parsedBody['id'];
            $this->pmb = $parsedBody['pmb'];
        }

        return true;
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $updatedUser =  Auth::acceptTerms($this->id, $this->pmb);

        if ($updatedUser) {
            return $response->withJson($updatedUser, 200);
        }

        return $response->withStatus(500);
    }
}
