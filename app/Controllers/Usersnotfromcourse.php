<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Usersnotfromcourse extends ResourceController
{
    use ResponseTrait;

    // get course missing users
    public function show($courseId = null)
    {
        $db = db_connect();
        $query = $db->query('SELECT U.id, U.name, U.last_name, U.email, U.nif FROM users AS U WHERE U.id NOT IN (SELECT user_id FROM users_courses WHERE course_id = ' . $courseId . ')')->getResultArray();
        return $this->respond($query, 200);
    }
}
