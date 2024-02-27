<?php

namespace App\Controllers;

use App\Models\UserCourseModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use stdClass;

class Registerexternal extends ResourceController
{
    use ResponseTrait;

    // create users and enroll them in course
    public function create()
    {
        $db = db_connect();
        $data = json_decode($this->request->getBody(), true);
        $model = new UserModel();
        $model2 = new UserCourseModel();
        $exists = $db->query('SELECT id, nif FROM users WHERE nif = "' . strtoupper($data['nif']) . '"')->getResultArray()[0];
        if ($exists != null) {
            $enrolled = $db->query('SELECT course_id AS id FROM users_courses WHERE user_id = "' . $exists['id'] . '"')->getResultArray();
            $found = false;
            for ($i = 0; $i < count($enrolled); $i++) {
                if ($enrolled[$i]['id'] == $data['course']) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $response = [
                        'status'   => 400,
                        'error'    => true,
                        'messages' => [
                          'error' => 'User already exists and is enrolled in course',
                        ]
                      ];
                return $this->response->setStatusCode(400)->setJSON($response);
            }
            $temp = new stdClass();
            $temp->user_id = $exists['id'];
            $temp->course_id = intval($data['course']);
            $temp->date_completed = date($data['completed']);
            $model2->insert($temp);
            $response = [
              'status'   => 200,
              'error'    => null,
              'messages' => [
                'success' => 'Existing user is enrolled in course',
                'data' => [
                  'userId' => $exists['id'],
                  'courseId' => intval($data['course'])
                ]
              ]
            ];
            return $this->respondCreated($response, 200);
        } else {
            $data['name'] = ucwords($data['name']);
            $data['last_name'] = ucwords($data['last_name']);
            $data['email'] = strtolower($data['email']);
            $data['nif'] = strtoupper($data['nif']);
            $userId = $model->insert($data, true);
            $temp = new stdClass();
            $temp->user_id = $userId;
            $temp->course_id = intval($data['course']);
            $temp->date_completed = date($data['completed']);
            $model2->insert($temp);
            $response = [
              'status'   => 201,
              'error'    => null,
              'messages' => [
                'success' => 'User created and enrolled in course',
                'data' => [
                  'userId' => $userId,
                  'courseId' => intval($data['course'])
                ]
              ]
            ];
            return $this->respondCreated($response, 200);
        }
    }
}
