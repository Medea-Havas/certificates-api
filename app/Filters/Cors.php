<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Cors implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        var_dump($request->headers());
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PATCH, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        // if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
        //     $origin = $_SERVER['HTTP_ORIGIN'];
        // } elseif (array_key_exists('HTTP_REFERER', $_SERVER)) {
        //     $origin = $_SERVER['HTTP_REFERER'];
        // } else {
        //     $origin = $_SERVER['REMOTE_ADDR'];
        // }
        // $allowed_domains = array(
        //     'http://localhost:3000',
        //     'https://certificates.medea.es'
        // );


        // if (in_array($origin, $allowed_domains)) {
        // header('Access-Control-Allow-Origin: *');
        // }

        // header("Access-Control-Allow-Headers: Origin, X-API-KEY, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Headers, Authorization, observe, enctype, Content-Length, X-Csrf-Token");
        // header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, PATCH, OPTIONS");
        // header("Access-Control-Allow-Credentials: true");
        // header("Access-Control-Max-Age: 3600");
        // header('content-type: application/json; charset=utf-8');
        // $method = $_SERVER['REQUEST_METHOD'];
        // if ($method == "OPTIONS") {
        //     header("HTTP/1.1 200 OK CORS");
        //     die();
        // }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
