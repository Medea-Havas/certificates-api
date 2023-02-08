<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CourseModel;

class Courses extends ResourceController
{
  use ResponseTrait;
  // get all course
  public function index()
  {
    $model = new CourseModel();
    $data = $model->findAll();
    return $this->respond($data, 200);
  }

  // get single course
  public function show($id = null)
  {
    $model = new CourseModel();
    $data = $model->getWhere(['id' => $id])->getResult();
    if ($data) {
      return $this->respond($data);
    } else {
      return $this->failNotFound('No course found with id ' . $id);
    }
  }

  // create a course
  public function create()
  {
    $model = new CourseModel();
    $data = json_decode($this->request->getBody());
    $model->insert($data);
    $response = [
      'status'   => 201,
      'error'    => null,
      'messages' => [
        'success' => 'Course created'
      ]
    ];

    return $this->respondCreated($response, 201);
  }

  // update course
  public function update($id = null)
  {
    $model = new CourseModel();
    $data = json_decode($this->request->getBody());
    // Insert to Database
    $model->update($id, $data);
    $response = [
      'status'   => 200,
      'error'    => null,
      'messages' => [
        'success' => 'Course updated'
      ]
    ];
    return $this->respond($response);
  }

  // delete course
  public function delete($id = null)
  {
    $model = new CourseModel();
    $data = $model->find($id);
    if ($data) {
      $model->delete($id);
      $response = [
        'status'   => 200,
        'error'    => null,
        'messages' => [
          'success' => 'Course deleted'
        ]
      ];
      return $this->respondDeleted($response);
    } else {
      return $this->failNotFound('No course found with id ' . $id);
    }
  }
}
