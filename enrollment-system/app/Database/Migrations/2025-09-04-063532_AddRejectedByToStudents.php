<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRejectedByToStudents extends Migration
{
    public function up()
    {
        // Add rejected_by field to store registrar name who rejected the student
        $fields = [
            'rejected_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Name of the registrar who rejected the student',
                'after'      => 'approved_by'
            ],
            'rejected_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'comment'    => 'Timestamp when the student was rejected',
                'after'      => 'rejected_by'
            ],
            'rejection_reason' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Reason for rejection',
                'after'      => 'rejected_at'
            ]
        ];
        
        $this->forge->addColumn('students', $fields);
    }

    public function down()
    {
        // Remove the added fields
        $this->forge->dropColumn('students', ['rejected_by', 'rejected_at', 'rejection_reason']);
    }
}
