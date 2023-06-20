<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseTemplateModel extends Model
{
    protected $table = 'courses_templates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['course_id', 'template_id'];
}
