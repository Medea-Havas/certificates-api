<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CourseTemplateModel;

class CourseTemplate extends ResourceController
{
    use ResponseTrait;

    // get single template
    public function show($id = null)
    {
        $model = new CourseTemplateModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        if ($data) {
            return $this->respond($data[0]);
        } else {
            return $this->failNotFound('No course-template found with id ' . $id);
        }
    }

    // create a template
    public function create()
    {
        $model = new CourseTemplateModel();
        $data = json_decode($this->request->getBody());
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
            'success' => 'Course-template created'
          ]
        ];

        return $this->respondCreated($response, 201);
    }

    // update template
    public function update($id = null)
    {
        $model = new CourseTemplateModel();
        $data = json_decode($this->request->getBody());
        // Insert to Database
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
            'success' => 'Course-template updated'
          ]
        ];
        return $this->respond($response);
    }

    // delete template
    public function delete($id = null)
    {
        $model = new CourseTemplateModel();
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            $response = [
              'status'   => 200,
              'error'    => null,
              'messages' => [
                'success' => 'Course-template deleted'
              ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No course-template found with id ' . $id);
        }
    }
}
