<?php
namespace MaspostAPI\Routes\Clientes;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Clientes.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Clientes;

use Slim\Http\Request;
use Slim\Http\Response;

class GET_ALL_AUTORIZADOS_ENTREGAR extends ENDPOINT
{
    private $queryData = [];
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        $params = $request->getQueryParams();

        if (!isset($params['pmb']) ||
            empty($params['pmb'])) {
            return $response->withStatus(400, 'Invalid PMB');
        }

        $this->queryData['pmb'] = $params['pmb'];

        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $clientes = Clientes::getAllAutorizadosEntregar($this->queryData['pmb']);

        if (!empty($clientes)) {
            return $response->withJson($clientes, 200);
        } else {
            return false;
        }
    }
}
