<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFaceEncodingToStudents extends Migration
{
    public function up()
    {
        // Add a LONGTEXT column to store base64-encoded pickled embeddings
        $fields = [
            'face_encoding' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'after' => 'email',
            ],
        ];
        $this->forge->addColumn('students', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('students', 'face_encoding');
    }
}


