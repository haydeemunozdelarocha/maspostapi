<?php
require_once('vendor/autoload.php');
require_once('routes/recepcion/GET_ALL.php');
require_once('routes/recepcion/GET_FLETERAS.php');
require_once('routes/clientes/GET_PLAN.php');
require_once('routes/clientes/GET_ALL_RECIBIR.php');
require_once('routes/captura/TEST.php');
require_once('routes/emails/SEND.php');
require_once('routes/express_pickup/CREATE.php');
require_once('routes/authorize_pickup/CREATE.php');
require_once('routes/auth/FORGOT_PASSWORD.php');
require_once('routes/auth/RESET_PASSWORD.php');
require_once('routes/auth/LOGIN.php');
require_once('routes/admin/SUMMARY.php');

$app = new Slim\App();
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->group('/recepcion', function () {
    $this->get('/all', MaspostAPI\Routes\Recepcion\GET_ALL::class);
    $this->get('/group', MaspostAPI\Routes\Recepcion\GET_GROUP::class);
    $this->post('/express_pickup', MaspostAPI\Routes\ExpressPickup\CREATE::class);
    $this->post('/authorize_pickup', MaspostAPI\Routes\AuthorizePickup\CREATE::class);
    $this->get('/fleteras', MaspostAPI\Routes\Recepcion\GET_FLETERAS::class);
});

$app->group('/clientes', function(){
    $this->get('/info_plan', MaspostAPI\Routes\Clientes\GET_PLAN::class);
    $this->get('/recibir', MaspostAPI\Routes\Clientes\GET_ALL_RECIBIR::class);
});

$app->group('/auth', function(){
    $this->post('/login', MaspostAPI\Routes\Auth\LOGIN::class);
    $this->post('/forgot_password', MaspostAPI\Routes\Auth\FORGOT_PASSWORD::class);
    $this->post('/reset_password', MaspostAPI\Routes\Auth\RESET_PASSWORD::class);
});

$app->group('/admin', function(){
    $this->post('/summary', MaspostAPI\Routes\Admin\SUMMARY::class);
});

$app->group('/captura', function(){
    $this->post('/test', MaspostAPI\Routes\Captura\TEST::class);
});

$app->group('/emails', function(){
    $this->post('/send', MaspostAPI\Routes\Emails\SEND::class);
});

$app->run();

?>


