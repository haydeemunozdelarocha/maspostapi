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
        $status = '';
        $date = '';

        if (!isset($params['pmb']) ||
            empty($params['pmb'])) {
            return $response->withStatus(400);
        }

        if (isset($params['status']) &&
            !empty($params['pmb'])) {
            $status = $params['status'];
        }

        if (isset($params['date']) &&
            !empty($params['date'])) {
            $date = $params['date'];
        }

        return $response->withJson(Recepcion::getAllRecepcionQuery($params['pmb'], $status, $date), 200);
    }
}
