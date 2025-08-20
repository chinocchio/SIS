<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'address'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'contact_number' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'guardian_name'  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'guardian_contact' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('student_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('student_profiles');
    }
}
