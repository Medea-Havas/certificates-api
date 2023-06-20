<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Stats extends ResourceController
{
    // use ResponseTrait;

    public function index()
    {
        $db = db_connect();
        $query = $db->query('SELECT COUNT(courses.id) AS `courses`, (SELECT COUNT(users.id) FROM users) AS `users` FROM courses');
        $queryObj = ['totals' => $query->getResult()];
        $query2 = $db->query('SELECT title, date_created FROM courses ORDER BY date_created DESC LIMIT 3');
        $query2Obj = ['lastCourses' => $query2->getResultArray()];
        $query3 = $db->query('SELECT CONCAT(name, " ", last_name) AS name, date_created FROM users ORDER BY date_created DESC LIMIT 3');
        $query3Obj = ['lastUsers' => $query3->getResultArray()];
        $data = array_merge($queryObj, $query2Obj, $query3Obj);
        return $this->respond($data, 200);
    }
}
