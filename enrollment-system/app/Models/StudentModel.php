<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'first_name','last_name','email','password',
        'grade_level','admission_type','strand_id','status'
    ];
}
