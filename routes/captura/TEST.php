<?php
namespace MaspostAPI\Routes\Captura;
require_once(__DIR__.'/../Endpoint.php');
require_once(__DIR__.'/../../repositories/Captura.php');
use MaspostAPI\Routes\ENDPOINT;
use MaspostAPI\Repositories\Captura;

use Slim\Http\Request;
use Slim\Http\Response;

class TEST extends ENDPOINT
{
    protected function check_4_access(Request $request, Response $response, array &$args)
    {
        return true;
    }

    protected function execute(Request $request, Response $response, array &$args)
    {
        $path = '/Users/haydeemunoz/PhpstormProjects/maspostwarehouseusers/api/test2.jpg';

        $res = Captura::readLabel($path);
        $barcode = Captura::readBarcodes('/Users/haydeemunoz/PhpstormProjects/maspostwarehouseusers/api/test3.png');
        return $response->withJson([
            'text' => $res,
            'barcode' => $barcode
        ], 200);
    }
}
