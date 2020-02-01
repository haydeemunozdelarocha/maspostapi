<?php
namespace MaspostAPI\Routes\ExpressPickup;

require_once(__DIR__.'/../../repositories/ExpressPickup.php');
require_once(__DIR__.'/../Endpoint.php');

use MaspostAPI\Repositories\ExpressPickup;
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Recepcion;
use Slim\Http\Request;
use Slim\Http\Response;

class UPDATE_GROUP extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {

        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {

        $parsedBody = $request->getParsedBody();

        if (!isset($parsedBody) ||
            empty($parsedBody)) {
            return $response->withStatus(400);
        }

        return $response->withJson(ExpressPickup::updateGroup($parsedBody), 200);
    }
}
