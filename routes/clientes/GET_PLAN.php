<?php
namespace MaspostAPI\Routes\Clientes;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Clientes.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Clientes;

use Slim\Http\Request;
use Slim\Http\Response;

class GET_PLAN extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $params = $request->getQueryParams();
        if (!isset($params['pmb']) ||
            empty($params['pmb'])) {
            return $response->withStatus(400);
        }

        return $response->withJson(Clientes::getPlanQuery($params['pmb']), 200);
    }
}
