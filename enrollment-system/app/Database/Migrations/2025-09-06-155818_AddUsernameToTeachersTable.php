<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUsernameToTeachersTable extends Migration
{
    public function up()
    {
        $fields = [
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'unique'     => true,
                'comment'    => 'Unique username for teacher login',
                'after'      => 'email'
            ]
        ];
        
        $this->forge->addColumn('teachers', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('teachers', 'username');
    }
}
