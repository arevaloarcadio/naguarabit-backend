<?php
namespace App\Traits;
use App\Helpers\Api as ApiHelper;

/**
 *
 */
trait ApiController
{
    public function sendResponse($resource)
    {
        $statusCode = isset($resource['error']['status_code']) ? $resource['error']['status_code'] : 200;
        return \Response::json($resource, $statusCode);
    }
}
