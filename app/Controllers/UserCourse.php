<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class UserCourse extends ResourceController
{
    use ResponseTrait;

    // get single user
    public function show($userId = null, $courseId = null)
    {
        $db = db_connect();
        $query = $db->query('SELECT U.name, U.last_name, U.nif, UC.date_completed, C.credits, C.file_number, C.hours, C.coords, C.certificate_image, C.certificate_image2 FROM users as U INNER JOIN users_courses AS UC ON u.id = ' . $userId . ' INNER JOIN courses AS C ON C.id = ' . $courseId . ' WHERE UC.user_id = ' . $userId . ' AND UC.course_id = ' . $courseId . '')->getResultArray()[0];
        return $this->respond($query, 200);
    }
}
