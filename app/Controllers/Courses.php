<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CourseModel;
use App\Models\CourseTemplateModel;

use function PHPUnit\Framework\isEmpty;

class Courses extends ResourceController
{
    use ResponseTrait;
    // get all course
    public function index()
    {
        $db = db_connect();
        $query = $db->query('SELECT C.id, C.title, C.accredited_by, C.accrediting_entity, C.file_number, C.city, C.credits, C.hours, C.tutors, C.content, C.certificate_thumbnail, C.certificate_image, C.certificate_image2, C.date_init, C.date_end, C.date_created, C.date_modified, CT.id AS course_template_id, T.id AS template_id, T.title AS template, T.coords FROM courses C LEFT JOIN courses_templates CT ON C.id = CT.course_id LEFT JOIN templates T ON CT.template_id = T.id')->getResultArray();
        return $this->respond($query, 200);
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
        helper(['form']);

        // Update certificate images
        $certificate_id = $this->request->getVar('certificate_id');
        $type = $this->request->getVar('type');
        if (isset($certificate_id) && isset($type)) {
            $db = db_connect();
            $update = null;
            switch ($type) {
                case 'thumbnail':
                    $certificate_thumbnail = $this->request->getFile('certificate_thumbnail');
                    if (empty($certificate_thumbnail)) {
                        return $this->fail('Certificate thumbnail is required');
                    }
                    $imageSize = $this->checkImageSize($certificate_thumbnail, 0.5);
                    if ($imageSize) return $this->fail('Image size ' . $imageSize . 'MB is bigger than allowed 0.5MB');
                    $certificate_thumbnail->move('./assets/certificates');
                    $update = $db->query('UPDATE courses SET certificate_thumbnail="' . $certificate_thumbnail->getName() . '" WHERE id=' . $certificate_id);
                    break;
                case 'image':
                    $certificate_image = $this->request->getFile('certificate_image');
                    if (empty($certificate_image)) {
                        return $this->fail('Certificate image is required');
                    }
                    $imageSize = $this->checkImageSize($certificate_image, 3);
                    if ($imageSize) return $this->fail('Image size ' . $imageSize . 'MB is bigger than allowed 3MB');
                    $certificate_image->move('./assets/certificates');
                    $update = $db->query('UPDATE courses SET certificate_image="' . $certificate_image->getName() . '" WHERE id=' . $certificate_id);
                    break;
                    case 'image2':
                        $certificate_image2 = $this->request->getFile('certificate_image2');
                        if (empty($certificate_image2)) {
                            return $this->fail('Certificate image 2 is required');
                        }
                        $imageSize = $this->checkImageSize($certificate_image2, 3);
                        if ($imageSize) return $this->fail('Image size ' . $imageSize . 'MB is bigger than allowed 3MB');
                        $certificate_image2->move('./assets/certificates');
                        $update = $db->query('UPDATE courses SET certificate_image2="' . $certificate_image2->getName() . '" WHERE id=' . $certificate_id);
                    break;
                default:
                    break;
            }
            if ($update) {
                $response = [
                    'status'   => 200,
                    'error'    => null,
                    'messages' => [
                      'success' => 'Image updated'
                    ]
                  ];
                  return $this->respondDeleted($response);
            }
            return $this->failNotFound('Course does not exist');
        }

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
        $certificate_thumbnail = $this->request->getFile('certificate_thumbnail');
        $certificate_image = $this->request->getFile('certificate_image');
        $certificate_image2 = $this->request->getFile('certificate_image2');
        $template_id = $this->request->getVar('template_id');

        if (empty($title)) {
            return $this->fail('Title is required ');
        }
        if (empty($file_number)) {
            return $this->fail('File number is required');
        }
        if (empty($credits)) {
            return $this->fail('Credits are required');
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

        $thumbnail_size = $this->checkImageSize($certificate_thumbnail, 0.5);
        if ($thumbnail_size) return $this->fail('Image size ' . $thumbnail_size . 'MB is bigger than allowed 0.5MB');
        $image_size = $this->checkImageSize($certificate_image, 3);
        if ($image_size) return $this->fail('Image size ' . $image_size . 'MB is bigger than allowed 3MB');
        $certificate_thumbnail->move('./assets/certificates');
        $certificate_image->move('./assets/certificates');

        if (!empty($certificate_image2)) {
            $image2_size = $this->checkImageSize($certificate_image2, 3);
            if ($image2_size) return $this->fail('Image size ' . $image2_size . 'MB is bigger than allowed 3MB');
            $certificate_image2->move('./assets/certificates');
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
          'certificate_thumbnail' => $certificate_thumbnail->getName(),
          'certificate_image' => $certificate_image->getName(),
          'certificate_image2' => is_null($certificate_image2) ? '' : $certificate_image2->getName()
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
        $response = [
          'status'   => 201,
          'error'    => null,
          'messages' => [
            'success' => 'Course created'
          ]
        ];

        return $this->respondCreated($response, 201);
    }

    private function checkImageSize($image, $maxSize)
    {
        if ($image->getSize() * 0.000001 > $maxSize) {
            return number_format((float)$image->getSize() * 0.000001, 2, '.', '');
        }
        return 0;
    }

    // update course
    public function update($id = null)
    {
        $model = new CourseModel();
        $data = json_decode($this->request->getBody());
        // Insert to Database
        if ($model->update($id, $data)) {
            $response = [
              'status'   => 200,
              'error'    => null,
              'messages' => [
                'success' => 'Course updated'
              ]
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
