<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Coursesnotfromuser extends ResourceController
{
    use ResponseTrait;

    // get user missing courses
    public function show($id = null)
    {
        $db = db_connect();
        $allCourses = $db->query('SELECT id, title FROM courses')->getResultArray();
        $userCourses = $db->query('SELECT C.id FROM courses as C INNER JOIN users_courses ON C.id = users_courses.course_id WHERE users_courses.user_id = ' . $id)->getResultArray();
        $tempAC = array();
        $tempUC = array();
        $tempRC = array();

        for ($i = 0; $i < count($allCourses); $i++) {
            array_push($tempAC, $allCourses[$i]);
        }
        for ($j = 0; $j < count($userCourses); $j++) {
            for($k = 0; $k < count($tempAC); $k++) {
                if ($tempAC[$k]['id'] == $userCourses[$j]['id']) {
                    // echo $tempAC[$k]['id'] . ' - ' . $userCourses[$j]['id'] . '***';
                    array_push($tempUC, $userCourses[$j]['id']);
                }
            }
        }
        for ($l = 0; $l < count($tempAC); $l++) {
            if (!in_array($tempAC[$l]['id'], $tempUC)) {
                array_push($tempRC, $tempAC[$l]);
            }
        }
        return $this->respond(array_values($tempRC), 200);
    }
}
