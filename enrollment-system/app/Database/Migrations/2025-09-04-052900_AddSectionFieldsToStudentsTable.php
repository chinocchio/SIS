<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSectionFieldsToStudentsTable extends Migration
{
    public function up()
    {
        $fields = [
            'section_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Current section assignment',
                'after'      => 'curriculum_id'
            ],
            'previous_section_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Previous section for tracking progression',
                'after'      => 'section_id'
            ],
            'previous_school_year' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'comment'    => 'Previous school year attended',
                'after'      => 'previous_section_id'
            ]
        ];
        
        $this->forge->addColumn('students', $fields);
        
        // Add foreign key constraints
        $this->forge->addForeignKey('section_id', 'sections', 'id', 'SET NULL', 'CASCADE', 'students');
        $this->forge->addForeignKey('previous_section_id', 'sections', 'id', 'SET NULL', 'CASCADE', 'students');
    }

    public function down()
    {
        // Remove foreign key constraints first
        $this->forge->dropForeignKey('students', 'students_section_id_foreign');
        $this->forge->dropForeignKey('students', 'students_previous_section_id_foreign');
        
        // Remove columns
        $this->forge->dropColumn('students', ['section_id', 'previous_section_id', 'previous_school_year']);
    }
}
