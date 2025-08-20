<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'name'          => ['type'=>'VARCHAR','constraint'=>20], // e.g. 7-A, 11-STEM-A
            'grade_level'   => ['type'=>'INT'],
            'strand_id'     => ['type'=>'INT','unsigned'=>true,'null'=>true], // for SHS
            'school_year_id'=> ['type'=>'INT','unsigned'=>true],
            'capacity_min'  => ['type'=>'INT','default'=>35],
            'capacity_max'  => ['type'=>'INT','default'=>40],
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('school_year_id','school_years','id','CASCADE','CASCADE');
        $this->forge->createTable('sections');
    }

    public function down()
    {
        $this->forge->dropTable('sections');
    }
}
