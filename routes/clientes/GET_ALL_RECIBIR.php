<?php
namespace MaspostAPI\Routes\Clientes;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Clientes.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Clientes;

use Slim\Http\Request;
use Slim\Http\Response;

class GET_ALL_RECIBIR extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $clientes = Clientes::getAllRecibir();

        if (!empty($clientes)) {
            return $response->withJson($clientes, 200);
        } else {
            return false;
        }
    }
}
