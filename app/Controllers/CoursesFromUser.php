<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class CoursesFromUser extends ResourceController
{
    use ResponseTrait;

    // get user courses
    public function show($id = null)
    {
        $db = db_connect();
        $query = $db->query('SELECT C.id, C.title, C.date_init, C.date_end, C.file_number, C.credits, C.certificate_thumbnail  FROM courses as C INNER JOIN users_courses ON C.id = users_courses.course_id WHERE users_courses.user_id = ' . $id)->getResultArray();
        return $this->respond($query, 200);
    }
}
