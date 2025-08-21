<?php

namespace App\Models;

use CodeIgniter\Model;

class StrandModel extends Model
{
    protected $table = 'strands';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'description', 'is_active'
    ];
    
    protected $useTimestamps = true;
    
    public function getActiveStrands()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    public function getStrandsByGradeLevel($gradeLevel)
    {
        // SHS strands (Grade 11-12)
        if ($gradeLevel >= 11) {
            return $this->where('is_active', 1)->findAll();
        }
        return []; // JHS doesn't have strands
    }
    
    public function checkTableExists()
    {
        try {
            $result = $this->db->query("SHOW TABLES LIKE 'strands'");
            return $result->getResult() ? true : false;
        } catch (\Exception $e) {
            log_message('error', 'Error checking strands table: ' . $e->getMessage());
            return false;
        }
    }
}
