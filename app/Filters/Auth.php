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
        $token = $request->getHeaderLine('Token');

        // Check if token is null or empty
        if(is_null($token) || empty($token)) {
            $response = service('response');
            $response->setBody('Access denied. No token found');
            $response->setStatusCode(401);
            return $response;
        }

        // Check token is valid
        $db = db_connect();
        $query = $db->query("SELECT id FROM admins WHERE token = '" . $token . "'")->getResult()[0]->id;
        if (!($query)) {
            $response = service('response');
            $response->setBody('Access denied. Token does not match.');
            $response->setStatusCode(401);
            return $response;
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
