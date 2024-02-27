<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Emitcertificate extends ResourceController
{
    use ResponseTrait;

    public function show($nif = null)
    {
        if (!$nif) {
            return $this->failNotFound('No nif is present');
        }
        // Get userID from NIF
        $db = db_connect();
        $userId = $db->query('SELECT id FROM users WHERE nif = "' . strtoupper($nif) . '"')->getResultArray()[0];
        if (!$userId) {
            return $this->failNotFound('User not found');
        }
        $courseId = $db->query('SELECT course_id FROM users_courses WHERE `user_id` = ' . $userId['id'] . '')->getResultArray()[0];
        if (!$courseId) {
            return $this->failNotFound('Course not found');
        }
        $response = [
          'status'   => 200,
          'error'    => false,
          'data' => [
            'userId' => intval($userId['id']),
            'courseId' => intval($courseId['course_id'])
          ]
        ];
        return $this->respond($response);
    }
}
