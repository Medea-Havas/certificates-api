<?php

namespace App\Models;

use CodeIgniter\Model;

class UserCourseModel extends Model
{
    protected $table = 'users_courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'date_completed', 'certificate_token', 'date_created', 'date_modified'];
}
