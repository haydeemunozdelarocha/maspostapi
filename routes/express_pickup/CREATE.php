<?php
namespace MaspostAPI\Routes\ExpressPickup;

require_once(__DIR__.'/../../repositories/ExpressPickup.php');
require_once(__DIR__.'/../Endpoint.php');

use MaspostAPI\Repositories\ExpressPickup;
use MaspostAPI\Routes\ENDPOINT;
use Slim\Http\Request;
use Slim\Http\Response;

class CREATE extends ENDPOINT
{
    private $data;

    protected function check_1_parameters(Request $request, Response $response, array &$args)
    {
        // if parent returns FALSE, this route request is failed
        if (!parent::check_1_parameters($request, $response, $args)) {
            // break this request
            return false;
        }

        $parsedBody = $request->getParsedBody();

        if (!isset($parsedBody['pmb']) ||
            empty($parsedBody['pmb'])) {
            return $response->withStatus(400);
        }

        if (!isset($parsedBody['ids']) ||
            empty($parsedBody['ids'])) {
            return $response->withStatus(400);
        }

        if (!isset($parsedBody['date']) ||
            empty($parsedBody['date'])) {
            return $response->withStatus(400);
        }

        if (!isset($parsedBody['time']) ||
            empty($parsedBody['time'])) {
            return $response->withStatus(400);
        }

        $this->data = [
            'pmb' => $parsedBody['pmb'],
            'date' => $parsedBody['date'],
            'time' => $parsedBody['time'],
            'ids' => $parsedBody['ids']
        ];

        return true;
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $result = ExpressPickup::create($this->data['ids'], $this->data['date'], $this->data['time']);

        return $response->withJson($result, 200);
    }
}
