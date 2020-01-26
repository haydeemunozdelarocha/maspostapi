<?php
namespace MaspostAPI\Routes\Clientes;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Clientes.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Clientes;

use Slim\Http\Request;
use Slim\Http\Response;

class GET_ACCOUNT_BALANCE extends ENDPOINT
{
    private $queryData;

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        $params = $request->getQueryParams();
        $this->queryData = [];

        if (!isset($params['pmb']) ||
            empty($params['pmb'])) {
            return $response->withStatus(400, 'Invalid PMB');
        }

        $this->queryData['pmb'] = $params['pmb'];

        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $estadoDeCuenta = Clientes::getAccountBalance($this->queryData['pmb']);

        if (!empty($estadoDeCuenta)) {
            return $response->withJson($estadoDeCuenta, 200);
        } else {
            return false;
        }
    }
}
