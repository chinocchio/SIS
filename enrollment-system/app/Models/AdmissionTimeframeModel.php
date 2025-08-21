<?php

namespace App\Models;

use CodeIgniter\Model;

class AdmissionTimeframeModel extends Model
{
    protected $table = 'admission_timeframes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'school_year_id', 'start_date', 'end_date', 'is_active'
    ];
    
    protected $useTimestamps = true;
    
    public function isAdmissionOpen()
    {
        $today = date('Y-m-d');
        
        $timeframe = $this->where('is_active', 1)
                          ->where('start_date <=', $today)
                          ->where('end_date >=', $today)
                          ->first();
        
        return $timeframe !== null;
    }
    
    public function getCurrentTimeframe()
    {
        return $this->where('is_active', 1)->first();
    }
    
    public function getAllTimeframesWithSchoolYear()
    {
        return $this->select('admission_timeframes.*, school_years.name as school_year_name')
                    ->join('school_years', 'school_years.id = admission_timeframes.school_year_id')
                    ->findAll();
    }
}
