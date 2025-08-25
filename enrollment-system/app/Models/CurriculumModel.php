<?php

namespace App\Models;

use CodeIgniter\Model;

class CurriculumModel extends Model
{
    protected $table = 'curriculums';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'level', 'track', 'description', 'is_active'
    ];
    
    protected $useTimestamps = true;
    
    public function getCurriculumsByLevel($level)
    {
        return $this->where('level', $level)
                    ->where('is_active', 1)
                    ->findAll();
    }
    
    public function getCurriculumsByLevelAndTrack($level, $track = null)
    {
        $builder = $this->where('level', $level)
                        ->where('is_active', 1);
        
        if ($track) {
            $builder->where('track', $track);
        }
        
        return $builder->findAll();
    }
    
    public function getAllActiveCurriculums()
    {
        return $this->where('is_active', 1)
                    ->orderBy('level', 'ASC')
                    ->orderBy('track', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
    
    public function getCurriculumWithDetails($id)
    {
        return $this->find($id);
    }
}
