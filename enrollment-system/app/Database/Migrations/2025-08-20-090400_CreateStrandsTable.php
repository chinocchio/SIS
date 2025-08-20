<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStrandsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'name'          => ['type'=>'VARCHAR','constraint'=>50], // e.g. STEM, ABM, HUMSS, TVL
            'description'   => ['type'=>'TEXT','null'=>true],
            'is_active'     => ['type'=>'BOOLEAN','default'=>1],
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('strands');
    }

    public function down()
    {
        $this->forge->dropTable('strands');
    }
}
