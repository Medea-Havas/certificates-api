<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Courseusersnotenrolled extends ResourceController
{
    use ResponseTrait;

    // get users
    public function show($courseId = null)
    {
        $db = db_connect();
        $query = $db->query('SELECT U.id, U.name, U.last_name FROM users as U WHERE U.id NOT IN (SELECT UC.user_id FROM users_courses AS UC WHERE UC.course_id = ' . $courseId . ')')->getResultArray();
        return $this->respond($query, 200);
    }
}
