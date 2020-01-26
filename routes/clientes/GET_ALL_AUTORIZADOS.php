<?php
namespace MaspostAPI\Routes\Clientes;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Clientes.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Clientes;

use Slim\Http\Request;
use Slim\Http\Response;

const AUTORIZADOS_TYPE_MAP = [
    0 => 'recibir',
    1 => 'entregar'
];

class GET_ALL_AUTORIZADOS extends ENDPOINT
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

        if (!isset($params['type']) ||
            empty($params['type'])) {
            $this->queryData['type'] = AUTORIZADOS_TYPE_MAP[0];
        }

        $this->queryData['type'] = $params['type'];

        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $autorizados = [];

        if ($this->queryData['type'] === AUTORIZADOS_TYPE_MAP[0]) {
            $autorizados = Clientes::getAllAutorizadosRecibir($this->queryData['pmb'], $this->queryData['type']);
        }

        if ($this->queryData['type'] === AUTORIZADOS_TYPE_MAP[1]) {
            $autorizados = Clientes::getAllAutorizadosEntregar($this->queryData['pmb'], $this->queryData['type']);
        }

        if (!empty($autorizados)) {
            return $response->withJson($autorizados, 200);
        } else {
            return false;
        }
    }
}
