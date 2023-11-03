<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Auth
        $header = $request->getHeaderLine('Authorization');
        $token = null;
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        // Prod Auth
        if(is_null($token) || empty($token)) {
            $tempToken = $request->getServer('REDIRECT_HTTP_AUTHORIZATION');
            if (preg_match('/Bearer\s(\S+)/', $tempToken, $matches)) {
                $token = $matches[1];
            }
        }

        // Check if token is null or empty
        if(is_null($token) || empty($token)) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }

        $db = db_connect();
        $query = $db->query('SELECT id FROM admins WHERE token = "' . $token . '"')->getResultArray()[0];
        if (!count($query)) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
