<?php
namespace MaspostAPI\Routes\AuthorizePickup;

require_once(__DIR__.'/../../repositories/AuthorizePickup.php');
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../email/EmailHelpers.php');
require_once(__DIR__.'/../../email/Email.php');

use MaspostAPI\EmailHelpers;
use MaspostAPI\Email;
use MaspostAPI\Repositories\Clientes;
use MaspostAPI\Repositories\AuthorizePickup;
use MaspostAPI\Routes\ENDPOINT;
use Slim\Http\Request;
use Slim\Http\Response;

class CREATE extends ENDPOINT
{
    private $data;

    protected function check_1_parameters(Request $request, Response $response, array &$args)
    {
        $this->data = [];

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

        if (!isset($parsedBody['name']) || empty(trim($parsedBody['name'])))
        {
            return $response->withStatus(400);
        }

        $this->data['pmb'] = $parsedBody['pmb'];
        $this->data['ids'] = $parsedBody['ids'];
        $this->data['name'] = $parsedBody['name'];

        return true;
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $result = AuthorizePickup::bulkCreate($this->data['ids'], $this->data['name']);

        if ($result) {
            $email = Clientes::getClientInfo($this->data['pmb'])['email'];
            $emailType = 'autorizado';

            $emailUser = new Email($email,
                EmailHelpers::getSubject($emailType, $this->data),
                EmailHelpers::getTemplate($this->data, $emailType),
                true,
                'autorizados@maspostwarehouse.com');

            if ($emailUser) {
                return $response->withStatus(200);
            }
        }
    }
}
