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
        $query = $db->query('SELECT U.name, U.last_name, U.nif, UC.date_completed, c.id AS course_id, C.title, C.credits, C.file_number, C.hours, C.certificate_image, C.certificate_image2 FROM users as U INNER JOIN users_courses AS UC ON u.id = ' . $userId . ' INNER JOIN courses AS C ON C.id = ' . $courseId . ' WHERE UC.user_id = ' . $userId . ' AND UC.course_id = ' . $courseId . '')->getResultArray()[0];
        $query2 = $db->query('SELECT coords FROM templates INNER JOIN courses_templates ON templates.id = courses_templates.template_id WHERE courses_templates.course_id = ' . $courseId . '')->getResultArray()[0];
        $queries = array_merge($query, $query2);
        return $this->respond($queries, 200);
    }
}
