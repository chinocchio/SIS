<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeachersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type'=>'INT','constraint'=>11,'unsigned'=>true,'auto_increment'=>true],
            'first_name'    => ['type'=>'VARCHAR','constraint'=>100],
            'last_name'     => ['type'=>'VARCHAR','constraint'=>100],
            'email'         => ['type'=>'VARCHAR','constraint'=>100,'unique'=>true],
            'password'      => ['type'=>'VARCHAR','constraint'=>255],
            'specialization'=> ['type'=>'VARCHAR','constraint'=>100,'null'=>true],
            'is_active'     => ['type'=>'BOOLEAN','default'=>1],
            'created_at'    => ['type'=>'DATETIME','null'=>true],
            'updated_at'    => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('teachers');
    }

    public function down()
    {
        $this->forge->dropTable('teachers');
    }
}
