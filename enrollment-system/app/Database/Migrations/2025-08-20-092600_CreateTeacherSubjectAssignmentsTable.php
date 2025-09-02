<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeacherSubjectAssignmentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'teacher_id'    => ['type'=>'INT','unsigned'=>true],
            'subject_id'    => ['type'=>'INT','unsigned'=>true],
            'section_id'    => ['type'=>'INT','unsigned'=>true],
            'school_year_id'=> ['type'=>'INT','unsigned'=>true],
            'is_active'     => ['type'=>'BOOLEAN','default'=>1],
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('teacher_id','teachers','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('subject_id','subjects','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('section_id','sections','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('school_year_id','school_years','id','CASCADE','CASCADE');
        $this->forge->createTable('teacher_subject_assignments');
    }

    public function down()
    {
        $this->forge->dropTable('teacher_subject_assignments');
    }
}
