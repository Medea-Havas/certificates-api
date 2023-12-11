<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Usersfromcourse extends ResourceController
{
    use ResponseTrait;

    // get course users
    public function show($id = null)
    {
        $db = db_connect();
        $query = $db->query('SELECT U.id, U.name, U.last_name, U.email, U.nif, R.date_completed FROM users as U INNER JOIN users_courses as R ON R.user_id = U.id WHERE R.course_id = ' . $id)->getResultArray();
        return $this->respond($query, 200);
    }

    // delete course user
    public function delete($userId = null, $courseId = null)
    {
        $db = db_connect();
        $query = $db->query('DELETE FROM users_courses WHERE user_id = ' . $userId . ' AND course_id = ' . $courseId)->getResult();
        if ($query) {
            $response = [
              'status'   => 200,
              'error'    => null,
              'messages' => [
                'success' => 'User course deleted'
              ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No user course found with student id ' . $userId . ' and course id ' . $courseId);
        }
    }
}
