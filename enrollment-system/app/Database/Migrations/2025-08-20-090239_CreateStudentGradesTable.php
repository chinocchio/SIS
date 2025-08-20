<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentGradesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'student_id'    => ['type'=>'INT','unsigned'=>true],
            'subject_id'    => ['type'=>'INT','unsigned'=>true],
            'school_year_id'=> ['type'=>'INT','unsigned'=>true],
            'grade'         => ['type'=>'DECIMAL','constraint'=>'5,2'], // e.g. 87.50
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id','students','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('subject_id','subjects','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('school_year_id','school_years','id','CASCADE','CASCADE');
        $this->forge->createTable('student_grades');
    }

    public function down()
    {
        $this->forge->dropTable('student_grades');
    }
}
