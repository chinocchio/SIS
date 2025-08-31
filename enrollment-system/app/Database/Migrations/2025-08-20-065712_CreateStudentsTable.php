<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'lrn' => [
                'type'       => 'VARCHAR',
                'constraint' => 12,
                'comment'    => 'Learner Reference Number (12 digits)',
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'middle_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'full_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
                'comment'    => 'Complete name as shown in SF9 document',
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'birth_date' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'gender' => [
                'type'       => "ENUM('Male','Female')",
                'null'       => true,
            ],
            'grade_level' => [
                'type'       => 'INT',
                'constraint' => 2,
                'comment'    => '7-12',
            ],
            'previous_grade_level' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => true,
                'comment'    => 'Previous grade level for tracking progression',
            ],
            'admission_type' => [
                'type'       => "ENUM('regular','transferee','re-enroll','promoted')",
                'default'    => 'regular',
                'null'       => true,
            ],
            'enrollment_type' => [
                'type'       => "ENUM('new','transferee','returning')",
                'default'    => 'new',
            ],
            'previous_school' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'strand_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'curriculum_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'status' => [
                'type'       => "ENUM('draft','pending','approved','rejected')",
                'default'    => 'draft',
            ],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('lrn');
        $this->forge->addKey('strand_id');
        $this->forge->addKey('curriculum_id');
        
        $this->forge->createTable('students');
    }

    public function down()
    {
        $this->forge->dropTable('students');
    }
}
