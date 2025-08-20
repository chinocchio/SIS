<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SchoolYearSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'name' => '2025-2026',
            'start_date' => '2025-06-01',
            'end_date' => '2026-03-31',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('school_years')->insert($data);
    }
}
