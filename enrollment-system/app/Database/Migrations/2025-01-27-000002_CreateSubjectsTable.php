<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubjectsTable extends Migration
{
    public function up()
    {
        // Check if table already exists
        if ($this->db->tableExists('subjects')) {
            // Table exists, modify it to add missing columns
            $this->modifyExistingSubjectsTable();
        } else {
            // Table doesn't exist, create it
            $this->createNewSubjectsTable();
        }
    }
    
    private function createNewSubjectsTable()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'curriculum_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'units' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,1',
                'null'       => false,
                'default'    => 1.0,
            ],
            'is_core' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
                'comment'    => '1 = Core subject, 0 = Elective subject',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => '1 = Active, 0 = Inactive',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('curriculum_id');
        $this->forge->addKey('code');
        
        // Add foreign key constraint
        $this->forge->addForeignKey('curriculum_id', 'curriculums', 'id', 'CASCADE', 'CASCADE');
        
        // Create unique constraint for code within curriculum
        $this->forge->addUniqueKey(['curriculum_id', 'code']);
        
        $this->forge->createTable('subjects');
    }
    
    private function modifyExistingSubjectsTable()
    {
        // Add missing columns if they don't exist
        $fields = [
            'curriculum_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'units' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,1',
                'null'       => false,
                'default'    => 1.0,
            ],
            'is_core' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 0,
                'comment'    => '1 = Core subject, 0 = Elective subject',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
                'comment'    => '1 = Active, 0 = Inactive',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => date('Y-m-d H:i:s'),
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => date('Y-m-d H:i:s'),
            ],
        ];
        
        // Add each field if it doesn't exist
        foreach ($fields as $fieldName => $fieldDef) {
            if (!$this->db->fieldExists($fieldName, 'subjects')) {
                $this->forge->addColumn('subjects', [$fieldName => $fieldDef]);
            }
        }
        
        // Add indexes if they don't exist
        $this->forge->addKey('curriculum_id');
        $this->forge->addKey('code');
        
        // Add foreign key constraint if it doesn't exist
        try {
            $this->forge->addForeignKey('curriculum_id', 'curriculums', 'id', 'CASCADE', 'CASCADE');
        } catch (\Exception $e) {
            // Foreign key might already exist
        }
        
        // Add unique constraint if it doesn't exist
        try {
            $this->forge->addUniqueKey(['curriculum_id', 'code']);
        } catch (\Exception $e) {
            // Unique constraint might already exist
        }
    }

    public function down()
    {
        $this->forge->dropTable('subjects');
    }
}
