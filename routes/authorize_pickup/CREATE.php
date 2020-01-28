<?php
namespace MaspostAPI\Routes\AuthorizePickup;

require_once(__DIR__.'/../../repositories/AuthorizePickup.php');
require_once(__DIR__.'/../Endpoint.php');

use MaspostAPI\Repositories\AuthorizePickup;
use MaspostAPI\Routes\ENDPOINT;
use Slim\Http\Request;
use Slim\Http\Response;

class CREATE extends ENDPOINT
{
    private $pmb;
    private $ids = [];
    private $authorizedName = '';

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

        if (!isset($parsedBody['authorized_name']) || empty(trim($parsedBody['authorized_name'])))
        {
            return $response->withStatus(400);
        }

        $this->pmb = $parsedBody['pmb'];
        $this->ids = $parsedBody['ids'];
        $this->authorizedName = $parsedBody['authorized_name'];

        return true;
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $result = AuthorizePickup::bulkCreate($this->ids, $this->authorizedName);

        if ($result) {
            return $response->withStatus(200);
        }
    }
}
