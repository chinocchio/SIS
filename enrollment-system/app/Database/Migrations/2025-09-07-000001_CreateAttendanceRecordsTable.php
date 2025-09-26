<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAttendanceRecordsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'subject_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false,
            ],
            'recorded_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['student_id']);
        $this->forge->addKey(['subject_id']);
        $this->forge->createTable('attendance_records', true);
    }

    public function down()
    {
        $this->forge->dropTable('attendance_records', true);
    }
}


