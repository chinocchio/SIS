<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCurriculumIdToStudentsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('students', [
            'curriculum_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'strand_id'
            ]
        ]);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('curriculum_id', 'curriculums', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Remove foreign key constraint first
        $this->db->query('ALTER TABLE students DROP FOREIGN KEY students_curriculum_id_foreign');
        
        // Remove the column
        $this->forge->dropColumn('students', 'curriculum_id');
    }
}
