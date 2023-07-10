<?php

namespace App\Controllers;

use App\Models\UserCourseModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class UsersCourses extends ResourceController
{
    use ResponseTrait;

    // get all user
    public function index()
    {
        $model = new UserCourseModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    // get single user
    public function show($id = null)
    {
        $model = new UserCourseModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        $tempData = count($data) ? $data[0] : [];
        if ($tempData) {
            return $this->respond($tempData);
        } else {
            return $this->failNotFound('No courses found for user with id ' . $id);
        }
    }

    // get courses for user
    public function userCourses($id = null)
    {
        $model = new UserCourseModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No course-user found with id ' . $id);
        }
    }

    // create a user
    public function create()
    {
        $model = new UserCourseModel();
        $data = json_decode($this->request->getBody());
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
            'success' => 'User created'
          ]
        ];

        return $this->respondCreated($response, 201);
    }

    // update user
    public function update($id = null)
    {
        $model = new UserCourseModel();
        $data = json_decode($this->request->getBody());
        // Insert to Database
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
            'success' => 'User updated'
          ]
        ];
        return $this->respond($response);
    }

    // delete user
    public function delete($userId = null, $courseId = null)
    {
        $db = db_connect();
        $query = $db->query('SELECT id FROM users_courses WHERE user_id=' . $userId . ' AND course_id=' . $courseId . '')->getResultArray();
        if (count($query)) {
            $model = new UserCourseModel();
            $model->delete($query[0]);
            $response = [
              'status'   => 200,
              'error'    => null,
              'messages' => [
                'success' => 'User ' . $userId . ' removed from course ' . $courseId
              ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No user enrolled in that course');
        }
    }
}
