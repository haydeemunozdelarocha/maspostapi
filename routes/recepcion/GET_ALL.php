<?php
namespace MaspostAPI\Routes\Recepcion;

require_once(__DIR__.'/../../repositories/Recepcion.php');
require_once(__DIR__.'/../Endpoint.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Recepcion;
use Slim\Http\Request;
use Slim\Http\Response;

class GET_ALL extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $params = $request->getQueryParams();
        $queryData = [];

        if (!isset($params['pmb']) ||
            empty($params['pmb'])) {
            return $response->withStatus(400, 'Invalid PMB: '.$params['pmb']);
        }

        $queryData['pmb'] = $params['pmb'];

        if (isset($params['status']) &&
            !empty($params['pmb'])) {
            $queryData['status'] = $params['status'];
        }

        if (isset($params['month']) &&
            !empty($params['month'])) {
            $queryData['month'] = $params['month'];
        }

        if (isset($params['year']) &&
            !empty($params['year'])) {
            $queryData['year'] = $params['year'];
        }

        return $response->withJson(Recepcion::getAllRecepcionQuery($queryData), 200);
    }
}
