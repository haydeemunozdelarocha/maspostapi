<?php
namespace MaspostAPI\Routes\ExpressPickup;

require_once(__DIR__.'/../../repositories/ExpressPickup.php');
require_once(__DIR__.'/../Endpoint.php');

use MaspostAPI\Repositories\ExpressPickup;
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Recepcion;
use Slim\Http\Request;
use Slim\Http\Response;

class UPDATE_ONE extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $route = $request->getAttribute('route');
        $id = $route->getArgument('id');

        if (!isset($id) ||
            empty($id)) {
            return $response->withStatus(400);
        }

        return $response->withJson(ExpressPickup::updateOne($id), 200);
    }
}
