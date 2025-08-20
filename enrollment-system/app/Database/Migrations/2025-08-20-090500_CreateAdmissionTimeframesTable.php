<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdmissionTimeframesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'school_year_id'=> ['type'=>'INT','unsigned'=>true],
            'start_date'    => ['type'=>'DATE'],
            'end_date'      => ['type'=>'DATE'],
            'is_active'     => ['type'=>'BOOLEAN','default'=>1],
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('school_year_id','school_years','id','CASCADE','CASCADE');
        $this->forge->createTable('admission_timeframes');
    }

    public function down()
    {
        $this->forge->dropTable('admission_timeframes');
    }
}
