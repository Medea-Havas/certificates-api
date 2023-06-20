<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Report extends ResourceController
{
    use ResponseTrait;

    // get single user
    public function show($userId = null, $courseId = null)
    {
        $db = db_connect();
        $query = $db->query('SELECT C.title, C.accredited_by, U.name, U.last_name, U.nif, UC.date_completed, C.file_number, C.city, C.credits, C.hours, C.date_init, C.date_end, C.tutors, C.content FROM users as U INNER JOIN users_courses AS UC ON u.id = ' . $userId . ' INNER JOIN courses AS C ON C.id = ' . $courseId . ' WHERE UC.user_id = ' . $userId . ' AND UC.course_id = ' . $courseId . '')->getResultArray()[0];
        return $this->respond($query, 200);
    }
}
