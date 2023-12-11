<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CourseModel;
use App\Models\CourseTemplateModel;

class Courses extends ResourceController
{
    use ResponseTrait;

    // get all courses
    public function index()
    {
        helper('images_path');
        $db = db_connect();
        $query = $db->query('SELECT C.id, C.title, C.accredited_by, C.accrediting_entity, C.file_number, C.city, C.credits, C.hours, C.tutors, C.content, CONCAT("' . get_images_path() . '", C.certificate_thumbnail) as certificate_thumbnail, CONCAT("' . get_images_path() . '", C.certificate_image) as certificate_image, IF(C.certificate_image2 IS NULL or C.certificate_image2="","",CONCAT("' . get_images_path() . '", C.certificate_image2)) as certificate_image2, C.date_init, C.date_end, C.date_created, C.date_modified, CT.id AS course_template_id, T.id AS template_id, T.title AS template, T.coords FROM courses C LEFT JOIN courses_templates CT ON C.id = CT.course_id LEFT JOIN templates T ON CT.template_id = T.id')->getResultArray();
        return $this->respond($query, 200);
    }

    // get single course
    public function show($id = null)
    {
        helper('images_path');
        $db = db_connect();
        // $model = new CourseModel();
        $query = $db->query('SELECT C.id, C.title, C.accredited_by, C.accrediting_entity, C.file_number, C.city, C.credits, C.hours, C.tutors, C.content, CONCAT("' . get_images_path() . '", C.certificate_thumbnail) as certificate_thumbnail, CONCAT("' . get_images_path() . '", C.certificate_image) as certificate_image, IF(C.certificate_image2 IS NULL or C.certificate_image2="","",CONCAT("' . get_images_path() . '", C.certificate_image2)) as certificate_image2, C.date_init, C.date_end, C.date_created, C.date_modified, CT.id AS course_template_id, T.id AS template_id, T.title AS template, T.coords FROM courses C LEFT JOIN courses_templates CT ON C.id = CT.course_id LEFT JOIN templates T ON CT.template_id = T.id WHERE C.id = "' . $id . '"')->getResultArray()[0];
        // $data = $model->getWhere(['id' => $id])->getResult();
        if ($query) {
            return $this->respond($query);
        } else {
            return $this->failNotFound('No course found with id ' . $id);
        }
    }

    // create a course
    public function create()
    {
        helper(['form']);
        helper('images_path');

        // Create certificate
        $title = $this->request->getVar('title');
        $accredited_by = $this->request->getVar('accredited_by');
        $accrediting_entity = $this->request->getVar('accrediting_entity');
        $file_number = $this->request->getVar('file_number');
        $city = $this->request->getVar('city');
        $credits = $this->request->getVar('credits');
        $hours = $this->request->getVar('hours');
        $tutors = $this->request->getVar('tutors');
        $content = $this->request->getVar('content');
        $date_init = $this->request->getVar('date_init');
        $date_end = $this->request->getVar('date_end');
        $certificate_thumbnail = $this->request->getVar('certificate_thumbnail');
        $certificate_image = $this->request->getVar('certificate_image');
        $certificate_image2 = $this->request->getVar('certificate_image2');
        $template_id = $this->request->getVar('template_id');

        if (empty($title)) {
            return $this->fail('Title is required');
        }
        if (empty($date_init)) {
            return $this->fail('Initial date is required');
        }
        if (empty($date_end)) {
            return $this->fail('End date is required');
        }
        if (empty($credits)) {
            return $this->fail('Credits are required');
        }
        if (empty($file_number)) {
            return $this->fail('File number is required');
        }
        if (empty($template_id)) {
            return $this->fail('Template id is required');
        }
        if (empty($certificate_thumbnail)) {
            return $this->fail('Certificate thumbnail is required');
        }
        if (empty($certificate_image)) {
            return $this->fail('Certificate image is required');
        }

        $data = [
          'title' => $title,
          'accredited_by' => $accredited_by,
          'accrediting_entity' => $accrediting_entity,
          'file_number' => $file_number,
          'city' => $city,
          'credits' => $credits,
          'hours' => $hours,
          'tutors' => $tutors,
          'content' => $content,
          'date_init' => $date_init,
          'date_end' => $date_end,
          'certificate_thumbnail' => $certificate_thumbnail,
          'certificate_image' => $certificate_image,
          'certificate_image2' => $certificate_image2
        ];

        // Insert data
        $model = new CourseModel();
        $course_id = $model->insert($data, true);

        $model2 = new CourseTemplateModel();
        $data2 = [
          'course_id' => $course_id,
          'template_id' => $template_id
        ];
        $model2->insert($data2);

        $data['certificate_thumbnail'] = get_images_path() . $data['certificate_thumbnail'];
        $data['certificate_image'] = get_images_path() . $data['certificate_image'];
        $data['certificate_image2'] = get_images_path() . $data['certificate_image2'];
        $data['id'] = $course_id;
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
            'success' => 'Course created'
          ],
          'data' => $data
        ];

        return $this->respondCreated($response, 201);
    }

    // update course
    public function update($courseId = null)
    {
        helper('images_path');

        $model = new CourseModel();
        $data = json_decode($this->request->getBody());
        // Insert to Database
        if ($model->update($courseId, $data)) {
            $data->certificate_thumbnail = get_images_path() . $data->certificate_thumbnail;
            $data->certificate_image = get_images_path() . $data->certificate_image;
            $data->certificate_image2 = get_images_path() . $data->certificate_image2;
            $response = [
              'status'   => 200,
              'error'    => null,
              'messages' => [
                'success' => 'Course updated'
              ],
              'data' => $data
            ];
            return $this->respond($response);
        }
        return $this->failNotFound('No course with that id');
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
