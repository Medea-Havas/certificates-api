<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class CoursesNotFromUser extends ResourceController
{
    use ResponseTrait;

    // get user missing courses
    public function show($id = null)
    {
        $db = db_connect();
        $allCourses = $db->query('SELECT id, title FROM courses')->getResultArray();
        $userCourses = $db->query('SELECT C.id FROM courses as C INNER JOIN users_courses ON C.id = users_courses.course_id WHERE users_courses.user_id = ' . $id)->getResultArray();
        $tempUC = array();
        for ($j = 0; $j < count($userCourses); $j++) {
            array_push($tempUC, $userCourses[$j]['id']);
        }
        for ($i = 0; $i < count($allCourses); $i++) {
            if (in_array($allCourses[$i]['id'], $tempUC)) {
                unset($allCourses[$i]);
            }
        }
        return $this->respond(array_values($allCourses), 200);
    }
}
