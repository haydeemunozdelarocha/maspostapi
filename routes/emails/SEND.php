<?php
namespace MaspostAPI\Routes\Emails;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../email/EmailHelpers.php');
require_once(__DIR__.'/../../email/Email.php');

use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Email;
use MaspostAPI\EmailHelpers;

use Slim\Http\Request;
use Slim\Http\Response;

class SEND extends ENDPOINT
{

    private $data;
    private $type;

    protected function check_1_parameters(Request $request, Response $response, array &$args)
    {
        // if parent returns FALSE, this route request is failed
        if (!parent::check_1_parameters($request, $response, $args)) {
            // break this request
            return false;
        }

        $parsedBody = $request->getParsedBody();

        if (!isset($parsedBody['type']) ||
            empty($parsedBody['type'])) {
            return false;
        }

        if (!isset($parsedBody['data']) ||
            empty($parsedBody['data'])) {
            return false;
        }

        $this->type = $parsedBody['type'];
        $this->data = $parsedBody['data'];

        return true;
    }

    protected function check_2_body(Request $request, Response $response, array &$args)
    {
        return true;

    }

    protected function check_3_exists(Request $request, Response $response, array &$args)
    {
        return true;

    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    /**
     * Execute the Request
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    protected function execute(Request $request, Response $response, array &$args)
    {
        $template = EmailHelpers::getTemplate($this->data, $this->type);
        $subject = EmailHelpers::getSubject($this->type);
        $email = Clientes::getClientInfo($this->pmb)['email'];
        $mail = new Email($email, $subject, $template);

        if(!$mail->send())
        {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->getErrorInfo();
            $response->withStatus(500);
        } else {
            return $response->withJson('hi', 200);
        }
    }
}


