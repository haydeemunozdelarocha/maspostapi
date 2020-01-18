<?php
namespace MaspostAPI\Routes\Recepcion;
require_once(__DIR__.'/../Endpoint.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Recepcion;

require_once(__DIR__.'/../../repositories/Recepcion.php');

use Slim\Http\Request;
use Slim\Http\Response;

class GET_GROUP extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $parsedBody = $request->getParsedBody();
        $params = $request->getQueryParams();

        if (!isset($params['pmb']) ||
            empty($params['pmb'])) {
            return $response->withStatus(400);
        }

        if (!isset($parsedBody['ids']) ||
                empty($parsedBody['ids'])) {

            return $response->withStatus(400);
        }

        $recepcionGroupQuery = Recepcion::getGroupRecepcionQuery($params['pmb'], $parsedBody['ids']);
        return $response->withStatus(200)->withJson($recepcionGroupQuery);
    }
}
