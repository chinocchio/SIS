<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'student_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'document_type' => ['type' => 'VARCHAR', 'constraint' => 100],
            'file_path'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'status'      => ["type" => "ENUM('pending','approved','rejected')", 'default' => 'pending'],
            'uploaded_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('documents');
    }

    public function down()
    {
        $this->forge->dropTable('documents');
    }
}
