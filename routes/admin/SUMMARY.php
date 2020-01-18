<?php
namespace MaspostAPI\Routes\Admin;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/SummaryData.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\SummaryData;

use Slim\Http\Request;
use Slim\Http\Response;

class SUMMARY extends ENDPOINT
{
    private $data = [];
    private $type;

    protected function check_2_body(Request $request, Response $response, array &$args)
    {
        $parsedBody = $request->getParsedBody();

        $this->data = [];

        if (!$parsedBody ||
            (!isset($parsedBody['startDate']) ||
                empty($parsedBody['startDate']) ||
                !isset($parsedBody['endDate']) ||
                empty($parsedBody['endDate']) ||
                !isset($parsedBody['type']) ||
                empty($parsedBody['type']))) {

            return $response->withStatus(400)->withJson('Please enter start and end date.');
        } else {
            $this->data['startDate'] = $parsedBody['startDate'];
            $this->data['endDate']  = $parsedBody['endDate'];
            $this->type = $parsedBody['type'];
            return true;

        }
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $summary = SummaryData::getSummary($this->type, $this->data);

        if ($summary)
        {
            return $response->withJson($summary, 200);
        } else {
            return $response->withStatus(400);
        }
    }
}
