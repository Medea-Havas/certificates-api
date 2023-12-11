<?php

namespace App\Controllers;

use App\Models\UserCourseModel;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use stdClass;

class Loadusers extends ResourceController
{
    use ResponseTrait;

    // create users and enroll them in course
    public function create()
    {
        $db = db_connect();
        $data = json_decode($this->request->getBody(), true);
        $model = new UserModel();
        $model2 = new UserCourseModel();
        $excludedFromImport = array();
        for ($i = 0; $i < count($data); $i++) {
            $exists = $db->query('SELECT nif FROM users WHERE nif = "' . strtoupper($data[$i]['nif']) . '"')->getResultArray();
            if (!count($exists)) {
                $data[$i]['name'] = ucwords($data[$i]['name']);
                $data[$i]['last_name'] = ucwords($data[$i]['last_name']);
                $data[$i]['email'] = strtolower($data[$i]['email']);
                $data[$i]['nif'] = strtoupper($data[$i]['nif']);
                $userId = $model->insert($data[$i], true);
                $temp = new stdClass();
                $temp->user_id = $userId;
                $temp->course_id = intval($data[$i]['course']);
                $temp->date_completed = date("Y-m-d H:i:s");
                $model2->insert($temp);
            } else {
                array_push($excludedFromImport, strval($exists[0]['nif']));
            }
        }
        if (!count($excludedFromImport)) {
            $response = [
              'status'   => 201,
              'error'    => null,
              'messages' => [
                'success' => 'Users created and enrolled in course',
              ]
            ];
        } else {
            $response = [
                'status'   => 201,
                'error'    => true,
                'messages' => [
                  'success' => 'Some users already exist, so they are skipped',
                  'excluded' => $excludedFromImport
                ]
              ];
        }
        return $this->respondCreated($response, 201);
    }
}
