<?php
namespace MaspostAPI\Routes;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;
/**
 * Class ENDPOINT
 *
 * Abstract class to handle the default way a Endpoint should be implemented
 * provides the _invoke-function to SLIM and forces child classes to implement access check etc...
 */
abstract class ENDPOINT
{
    protected $c;

    /**
     * ENDPOINT constructor.
     * @param Slim\Container $container
     */
    public function __construct(Container $container)
    {
        $this->c = $container;
    }

    /**
     * The function that will be invoked when calling the endpoint
     *
     * Calls the following protected functions:
     * check_1_parameters
     * check_2_body
     * check_3_access
     * check_4_exists
     * execute
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        if (($res = $this->check_1_parameters($request, $response, $args)) !== true) {
            return ($res === false) ? $this->c->Responses->badRequest($response) : $res;
        }
        if (($res = $this->check_2_body($request, $response, $args)) !== true) {
            return ($res === false) ? $this->c->Responses->unprocessableEntity($response) : $res;
        }
        if (($res = $this->check_3_exists($request, $response, $args)) !== true) {
            return ($res === false) ? $this->c->Responses->notFound($response) : $res;
        }
        if (($res = $this->check_4_access($request, $response, $args)) !== true) {
            return ($res === false) ? $this->c->Responses->accessDenied($response) : $res;
        }
        $ret = $this->execute($request, $response, $args);
        if (!$ret instanceof Response) {
            // Return server error if execute didin't provide a valid response
            $this->c->logger->critical('Execute didnt return a valid response', [$ret]);
            return $this->c->Responses->serverError($response);
        }

        // Check for good responses to avoid activity log to be there on limits reached
        if($ret->getStatusCode() >= 200 && $ret->getStatusCode() <= 299) {
            // Post-Functions shouldnt fail the response anymore
            try {
                $this->post_1_activitylog($request, $args);
            } catch (\Exception $e){
                $this->c->logger->error('Error in post function: '.$e->getMessage(), $e->getTrace(), ['error' => $e]);
            }
        }


        return $ret;
    }


    /**
     * This method should check whether the given path and query parameters are in a valid format.
     * If the requests seems valid, it should return true.
     * If it returns false, a `400 (Bad Request)` response will automatically be sent back and the route won't be executed any further.
     * @see \UserlaneAPI\Dependencies\Utils\Responses::badRequest()
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return bool|Response
     */
    protected function check_1_parameters(Request $request, Response $response, array &$args)
    {
        return true;
    }

    /**
     * This method should check whether the given request body is in a valid format.
     * If the body seems valid, it should return true.
     * If it returns false, a `422 (Bad Request)` response will automatically be sent back and the route won't be executed any further.
     * @see \UserlaneAPI\Dependencies\Utils\Responses::unprocessableEntity()
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return bool|Response
     */
    protected function check_2_body(Request $request, Response $response, array &$args)
    {
        return true;
    }

    /**
     * This method should check whether the requested element exists.
     * If you do not return true here, a `404 (Not Found)` response is sent back and the route won't be executed any further.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return bool|Response
     */
    protected function check_3_exists(Request $request, Response $response, array &$args)
    {
        return true;
    }

    /**
     * This method should check whether the authenticated user has access to the requested element.
     * You can make sure that a user is authenticated beforehand in the `_router.php` file by adding the requireAuthenticated check as a middleware around the route.
     * This function should only check whether the authenticated user has the permission to handle the requested element.
     * If you do not return true here, a `403 (Forbidden)` response is sent back and the route won't be executed any further.
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return bool|Response
     */
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    /**
     * This method should perform the requested action and return its result.
     * Some parts of the action might already have been performed by the check methods
     * If the method doesn't return a Response, a `500 (Internal Server Error)` response is sent back instead
     * @see check_1_parameters()
     * @see check_2_body()
     * @see check_3_exists()
     * @see check_4_access()
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    abstract protected function execute(Request $request, Response $response, array &$args);


    /**
     * This method should log updates that have been made to the database by a loggedin user.
     *
     * At this point, you cannot change the response anymore.
     *
     * @param Request $request
     * @param array $args
     * @return void
     */
    protected function post_1_activitylog(Request $request, array &$args)
    {
        /*
         * empty function,
         * no logging by default
         */
    }
}
