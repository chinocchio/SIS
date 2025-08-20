<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StrandSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'STEM',
                'description' => 'Science, Technology, Engineering, and Mathematics',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'ABM',
                'description' => 'Accountancy, Business, and Management',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'HUMSS',
                'description' => 'Humanities and Social Sciences',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'TVL',
                'description' => 'Technical-Vocational-Livelihood',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('strands')->insertBatch($data);
    }
}
