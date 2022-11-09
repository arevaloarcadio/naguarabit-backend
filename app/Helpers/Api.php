<?php

namespace App\Helpers;
use League\OAuth2\Server\Exception\OAuthServerException;

class Api{

    public static function resource(){
        $resource = [];
        $resource['response'] = 'ERROR';
        $resource['data'] = [];
        $resource['error']  = [
            'code' => 0,
            'status_code' => 500,
            'message' => 'unknown',
            'type' => 'system'
        ];
        return $resource;
    }

    public static function setException(&$resource, \Exception $exception){
        $message = $exception->getMessage();

        $resource['error']['code'] = $exception->getCode();
        if ($exception instanceof OAuthServerException) {
           $resource['error']['status_code'] =  $exception->getHttpStatusCode();
          // $message  =

        }
        if((!$exception instanceof OAuthServerException) && method_exists($exception, 'getStatusCode')){
            $resource['error']['status_code'] =  $exception->getStatusCode();
            if($exception->getStatusCode() === 404 && empty($message)){
                $message = 'Page not found';
            }
        }
        $resource['error']['message'] = $message;
        $resource['error']['type']   = 'unknown';
    }

    public static function success(&$resource){
        $resource['response'] =  'OK';
        unset($resource['error']);
    }
    
    public static function setError(&$resource, $code = 0, $status_code = 500, $message ){
        $resource['response'] = 'ERROR';
        $resource['error']['code'] = $code;
        $resource['error']['status_code'] = $status_code;
        $resource['error']['message'] = $message;
        //$resource['error']['type']   = $type;
    }
}
