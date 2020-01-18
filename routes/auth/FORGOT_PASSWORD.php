<?php
namespace MaspostAPI\Routes\Auth;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Auth.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Auth;
use MaspostAPI\Email;
use MaspostAPI\EmailHelpers;
use Slim\Http\Request;
use Slim\Http\Response;

class FORGOT_PASSWORD extends ENDPOINT
{
    private $data = [];
    private $type;

    protected function check_2_body(Request $request, Response $response, array &$args)
    {
        $parsedBody = $request->getParsedBody();
        $this->data = [];

        if (!$parsedBody ||
            (!isset($parsedBody['email']) ||
                empty($parsedBody['email']) ||
                !isset($parsedBody['pmb']) ||
                empty($parsedBody['pmb']))) {

            return $response->withStatus(400)->withJson('Please enter an email and pmb number');
        } else {
            $this->data['email'] = $parsedBody['email'];
            $this->data['pmb']  = $parsedBody['pmb'];
            $this->type = 'forgot_password';
            return true;

        }
    }

    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $credentials = Auth::getCustomerCredentials($this->data['email']);

        if(!empty($credentials) && $this->data['pmb'] == $credentials[0]['pmb'])
        {
            $this->data['token'] = Auth::setTemporaryPassword($credentials[0]['id']);

            if($this->data['token'])
            {
                $mail = new Email($this->data['pmb'], EmailHelpers::getSubject('forgot_password'), EmailHelpers::getTemplate($this->data, $this->type));

                if(!$mail->send())
                {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->getErrorInfo();
                } else {
                    return $response->withStatus(200);
                }
            }
        } else {
            return false;
        }
    }
}

