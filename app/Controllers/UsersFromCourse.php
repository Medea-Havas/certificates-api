<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class UsersFromCourse extends ResourceController
{
  use ResponseTrait;

  // get course users
  public function show($id = null)
  {
    $db = db_connect();
    $query = $db->query('SELECT U.id, U.name, U.last_name, U.nif, R.date_completed FROM users as U INNER JOIN users_courses as R WHERE R.course_id = ' . $id)->getResultArray();
    return $this->respond($query, 200);
  }
}
