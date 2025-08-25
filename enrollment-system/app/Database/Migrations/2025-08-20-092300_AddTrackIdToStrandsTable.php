<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTrackIdToStrandsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('strands', [
            'track_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'id'
            ]
        ]);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('track_id', 'tracks', 'id', 'CASCADE', 'SET NULL');
    }

    public function down()
    {
        // Remove foreign key constraint first
        $this->db->query('ALTER TABLE strands DROP FOREIGN KEY strands_track_id_foreign');
        
        // Remove the column
        $this->forge->dropColumn('strands', 'track_id');
    }
}
