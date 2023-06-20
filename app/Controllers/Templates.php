<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\TemplateModel;

class Templates extends ResourceController
{
    use ResponseTrait;
    // get all templates
    public function index()
    {
        $model = new TemplateModel();
        $data = $model->findAll();
        return $this->respond($data, 200);
    }

    // get single template
    public function show($id = null)
    {
        $model = new TemplateModel();
        $data = $model->getWhere(['id' => $id])->getResult();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No template found with id ' . $id);
        }
    }

    // create a template
    public function create()
    {
        $model = new TemplateModel();
        $data = json_decode($this->request->getBody());
        $model->insert($data);
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
            'success' => 'Template created'
          ]
        ];

        return $this->respondCreated($response, 201);
    }

    // update template
    public function update($id = null)
    {
        $model = new TemplateModel();
        $data = json_decode($this->request->getBody());
        // Insert to Database
        $model->update($id, $data);
        $response = [
          'status'   => 200,
          'error'    => null,
          'messages' => [
            'success' => 'Template updated'
          ]
        ];
        return $this->respond($response);
    }

    // delete template
    public function delete($id = null)
    {
        $model = new TemplateModel();
        $data = $model->find($id);
        if ($data) {
            $model->delete($id);
            $response = [
              'status'   => 200,
              'error'    => null,
              'messages' => [
                'success' => 'Template deleted'
              ]
            ];
            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No template found with id ' . $id);
        }
    }
}
