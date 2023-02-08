<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;

class Users extends ResourceController
{
  use ResponseTrait;
  // get all user
  public function index()
  {
    $model = new UserModel();
    $data = $model->findAll();
    return $this->respond($data, 200);
  }

  // get single user
  public function show($id = null)
  {
    $model = new UserModel();
    $data = $model->getWhere(['id' => $id])->getResult();
    if ($data) {
      return $this->respond($data);
    } else {
      return $this->failNotFound('No user found with id ' . $id);
    }
  }

  // get courses for user
  public function userCourses($id = null)
  {
    $model = new UserModel();
    // $courseModel = new CourseModel();
    $data = $model->getWhere(['id' => $id])->getResult();
    if ($data) {
      return $this->respond($data);
    } else {
      return $this->failNotFound('No user found with id ' . $id);
    }
  }

  // create a user
  public function create()
  {
    $model = new UserModel();
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
    $model = new UserModel();
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
  public function delete($id = null)
  {
    $model = new UserModel();
    $data = $model->find($id);
    if ($data) {
      $model->delete($id);
      $response = [
        'status'   => 200,
        'error'    => null,
        'messages' => [
          'success' => 'User deleted'
        ]
      ];
      return $this->respondDeleted($response);
    } else {
      return $this->failNotFound('No user found with id ' . $id);
    }
  }
}
