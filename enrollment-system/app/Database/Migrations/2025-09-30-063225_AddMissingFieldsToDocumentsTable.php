<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToDocumentsTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Add missing columns only if they do not already exist
        $columnsToAdd = [];
        
        if (!$db->fieldExists('original_filename', 'documents')) {
            $columnsToAdd['original_filename'] = [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'Original filename of uploaded file',
                'after'      => 'file_path'
            ];
        }
        
        if (!$db->fieldExists('file_size', 'documents')) {
            $columnsToAdd['file_size'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'File size in bytes',
                'after'      => 'original_filename'
            ];
        }
        
        if (!$db->fieldExists('description', 'documents')) {
            $columnsToAdd['description'] = [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Optional description of the document',
                'after'      => 'file_size'
            ];
        }

        if (!empty($columnsToAdd)) {
            $this->forge->addColumn('documents', $columnsToAdd);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();
        
        // Remove columns if they exist
        if ($db->fieldExists('description', 'documents')) {
            $this->forge->dropColumn('documents', 'description');
        }
        
        if ($db->fieldExists('file_size', 'documents')) {
            $this->forge->dropColumn('documents', 'file_size');
        }
        
        if ($db->fieldExists('original_filename', 'documents')) {
            $this->forge->dropColumn('documents', 'original_filename');
        }
    }
}
