<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class CoursesFromUser extends ResourceController
{
  use ResponseTrait;

  // get user courses
  public function show($id = null)
  {
    $db = db_connect();
    $query = $db->query('SELECT * FROM courses INNER JOIN users_courses WHERE users_courses.user_id = ' . $id)->getResultArray();
    return $this->respond($query, 200);
  }
}
