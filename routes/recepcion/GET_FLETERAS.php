<?php
namespace MaspostAPI\Routes\Recepcion;
require_once(__DIR__.'/../Endpoint.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Recepcion;

require_once(__DIR__.'/../../repositories/Recepcion.php');

use Slim\Http\Request;
use Slim\Http\Response;

class GET_FLETERAS extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $fleteras= Recepcion::getFleteras();
        return $response->withStatus(200)->withJson($fleteras);
    }
}
