<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchoolYearsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'name'          => ['type'=>'VARCHAR','constraint'=>20], // e.g. 2025-2026
            'start_date'    => ['type'=>'DATE'],
            'end_date'      => ['type'=>'DATE'],
            'is_active'     => ['type'=>'BOOLEAN','default'=>0], // only one active at a time
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('school_years');
    }

    public function down()
    {
        $this->forge->dropTable('school_years');
    }
}
