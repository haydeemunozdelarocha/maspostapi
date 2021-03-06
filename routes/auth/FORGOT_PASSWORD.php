<?php
namespace MaspostAPI\Routes\Auth;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Auth.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Auth;
use MaspostAPI\Repositories\Clientes;

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

        if(!empty($credentials) && $this->data['pmb'] == $credentials['pmb'])
        {
            $this->data['token'] = Auth::setTemporaryPassword($credentials['id']);

            if($this->data['token'])
            {
                $email = Clientes::getClientInfo($this->data['pmb'])['email'];
                $mail = new Email($email, EmailHelpers::getSubject('forgot_password'), EmailHelpers::getTemplate($this->data, $this->type));

                if(!$mail->send())
                {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->getErrorInfo();
                    return $response->withStatus(422);
                } else {
                    return $response->withStatus(200);
                }
            }
        }

        return $response->withStatus(404);
    }
}

