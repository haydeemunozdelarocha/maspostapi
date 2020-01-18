<?php
namespace MaspostAPI\Routes\ExpressPickup;

require_once(__DIR__.'/../../repositories/ExpressPickup.php');
require_once(__DIR__.'/../Endpoint.php');

use MaspostAPI\Repositories\ExpressPickup;
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Recepcion;
use Slim\Http\Request;
use Slim\Http\Response;

class GET_ONE extends ENDPOINT
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

        if (!isset($params['id']) ||
            empty($params['id'])) {
            return $response->withStatus(400);
        }

        return $response->withJson(ExpressPickup::getOne($params['id']), 200);
    }
}
