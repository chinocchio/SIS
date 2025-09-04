<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeApprovedByToVarchar extends Migration
{
    public function up()
    {
        // Change approved_by field from INT to VARCHAR to store registrar names
        $fields = [
            'approved_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Name of the registrar who approved the student'
            ]
        ];
        
        $this->forge->modifyColumn('students', $fields);
    }

    public function down()
    {
        // Revert back to INT if needed
        $fields = [
            'approved_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true
            ]
        ];
        
        $this->forge->modifyColumn('students', $fields);
    }
}
