<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdmissionTimeframeSeeder extends Seeder
{
    public function run()
    {
        // Get the first school year ID
        $schoolYear = $this->db->table('school_years')->get()->getRowArray();
        
        if ($schoolYear) {
            // Set admission timeframe to include current date and future dates
            $currentYear = date('Y');
            $nextYear = $currentYear + 1;
            
            $data = [
                'school_year_id' => $schoolYear['id'],
                'start_date' => date('Y-m-d'), // Start from today
                'end_date' => $nextYear . '-12-31', // End at end of next year
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->table('admission_timeframes')->insert($data);
        }
    }
}
