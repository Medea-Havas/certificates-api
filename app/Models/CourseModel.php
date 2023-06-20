<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'accredited_by', 'accrediting_entity', 'file_number', 'city', 'credits', 'hours', 'tutors', 'content', 'certificate_thumbnail', 'certificate_image', 'certificate_image2', 'date_init', 'date_end', 'date_created', 'date_modified'];
}
