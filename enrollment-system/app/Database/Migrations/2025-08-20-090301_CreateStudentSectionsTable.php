<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentSectionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'student_id'    => ['type'=>'INT','unsigned'=>true],
            'section_id'    => ['type'=>'INT','unsigned'=>true],
            'school_year_id'=> ['type'=>'INT','unsigned'=>true],
            'rank_in_section'=> ['type'=>'INT','null'=>true], // position based on grades
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id','students','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('section_id','sections','id','CASCADE','CASCADE');
        $this->forge->addForeignKey('school_year_id','school_years','id','CASCADE','CASCADE');
        $this->forge->createTable('student_sections');
    }

    public function down()
    {
        $this->forge->dropTable('student_sections');
    }
}
