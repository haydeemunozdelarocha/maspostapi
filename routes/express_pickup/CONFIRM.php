<?php
namespace MaspostAPI\Routes\ExpressPickup;

require_once(__DIR__.'/../../repositories/ExpressPickup.php');
require_once(__DIR__.'/../Endpoint.php');

use MaspostAPI\Repositories\ExpressPickup;
use MaspostAPI\Routes\ENDPOINT;
use Slim\Http\Request;
use Slim\Http\Response;
require_once(__DIR__.'/../../email/EmailHelpers.php');
require_once(__DIR__.'/../../email/Email.php');
use MaspostAPI\Repositories\Clientes;

use MaspostAPI\Email;
use MaspostAPI\EmailHelpers;
class CONFIRM extends ENDPOINT
{
    private $id;
    private $dataToUpdate;

    protected function check_1_parameters(Request $request, Response $response, array &$args)
    {
        // if parent returns FALSE, this route request is failed
        if (!parent::check_1_parameters($request, $response, $args)) {
            // break this request
            return false;
        }

        $parsedBody = $request->getParsedBody();

        if (!isset($parsedBody['id']) ||
            empty($parsedBody['id'])) {
            return $response->withStatus(400);
        }

        $this->id = $parsedBody['id'];
        $this->dataToUpdate = $parsedBody['data'];
        return true;
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $updatedExpressPickup = ExpressPickup::updateOne($this->dataToUpdate);

        if ($updatedExpressPickup['confirmado'] == 1) {
            $email = Clientes::getClientInfo($updatedExpressPickup['paquetes'][0]['pmb'])['email'];

            $emailUser = new Email($email,
                EmailHelpers::getSubject('confirm_entrega_express', $updatedExpressPickup),
                EmailHelpers::getTemplate($updatedExpressPickup, 'confirm_entrega_express'));

            if(!$emailUser->send())
            {
                echo 'User email could not be sent.';
                echo 'Mailer Error: ' . $emailUser->getErrorInfo();
            } else {
                return $response->withJson($updatedExpressPickup, 200);
            }

            return $response->withJson($updatedExpressPickup, 200);
        }

        return false;
    }
}
