<?php

namespace App\Models;

use CodeIgniter\Model;

class SchoolYearModel extends Model
{
    protected $table = 'school_years';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'start_date', 'end_date', 'is_active'
    ];
    
    protected $useTimestamps = true;
    
    public function getActiveSchoolYear()
    {
        return $this->where('is_active', 1)->first();
    }
    
    public function activateSchoolYear($id)
    {
        // Deactivate all other school years
        // $this->set('is_active', 0)->update();
        $this->db->table($this->table)->update(['is_active' => 0]);
        
        // Activate the selected school year
        return $this->update($id, ['is_active' => 1]);
    }
    
    public function deactivateSchoolYear($id)
    {
        // Deactivate the specified school year
        return $this->update($id, ['is_active' => 0]);
    }
    
    public function createNewSchoolYear($data)
    {
        // Deactivate current active school year
        // $this->set('is_active', 0)->update();
        $data['is_active'] = 0;
        
        // Create new school year
        return $this->insert($data);
    }
}
