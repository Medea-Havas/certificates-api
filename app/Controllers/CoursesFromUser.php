<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Coursesfromuser extends ResourceController
{
    use ResponseTrait;

    // get user courses
    public function show($id = null)
    {
        helper('images_path');
        $db = db_connect();
        $query = $db->query('SELECT C.id, C.title, C.date_init, C.date_end, UC.date_completed, C.file_number, C.credits, CONCAT("' . get_images_path() . '", C.certificate_thumbnail) as certificate_thumbnail FROM courses as C INNER JOIN users_courses as UC ON C.id = UC.course_id WHERE UC.user_id = ' . $id)->getResultArray();
        return $this->respond($query, 200);
    }
}
