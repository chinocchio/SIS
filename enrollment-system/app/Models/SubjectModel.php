<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'curriculum_id', 'code', 'name', 'description', 'units', 'is_core', 'is_active'
    ];
    
    protected $validationRules = [
        'curriculum_id' => 'required|integer',
        'code' => 'required|min_length[2]|max_length[20]',
        'name' => 'required|min_length[2]|max_length[100]',
        'units' => 'required|numeric|greater_than[0]|less_than[10]'
    ];
    
    protected $validationMessages = [
        'curriculum_id' => [
            'required' => 'Curriculum is required',
            'integer' => 'Curriculum must be a valid selection'
        ],
        'code' => [
            'required' => 'Subject code is required',
            'min_length' => 'Subject code must be at least 2 characters',
            'max_length' => 'Subject code must not exceed 20 characters'
        ],
        'name' => [
            'required' => 'Subject name is required',
            'min_length' => 'Subject name must be at least 2 characters',
            'max_length' => 'Subject name must not exceed 100 characters'
        ],
        'units' => [
            'required' => 'Units are required',
            'numeric' => 'Units must be a number',
            'greater_than' => 'Units must be greater than 0',
            'less_than' => 'Units must be less than 10'
        ]
    ];
    
    protected $useTimestamps = true;
    
    /**
     * Get all subjects for a specific curriculum
     */
    public function getSubjectsByCurriculum($curriculumId)
    {
        return $this->where('curriculum_id', $curriculumId)
                    ->where('is_active', 1)
                    ->orderBy('code', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get all active subjects with curriculum information
     */
    public function getAllActiveSubjectsWithCurriculum()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('subjects s')
                    ->select('s.*, c.name as curriculum_name')
                    ->join('curriculums c', 'c.id = s.curriculum_id')
                    ->where('s.is_active', 1)
                    ->orderBy('c.name', 'ASC')
                    ->orderBy('s.code', 'ASC')
                    ->get();
        
        return $query->getResultArray();
    }
    
    /**
     * Get subject by code and curriculum
     */
    public function getSubjectByCodeAndCurriculum($code, $curriculumId)
    {
        return $this->where('code', $code)
                    ->where('curriculum_id', $curriculumId)
                    ->first();
    }
    
    /**
     * Check if subject code is unique within a curriculum
     */
    public function isCodeUniqueInCurriculum($code, $curriculumId, $excludeId = null)
    {
        $query = $this->where('code', $code)
                      ->where('curriculum_id', $curriculumId);
        
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        
        return $query->countAllResults() === 0;
    }
    
    /**
     * Get core subjects for a curriculum
     */
    public function getCoreSubjectsByCurriculum($curriculumId)
    {
        return $this->where('curriculum_id', $curriculumId)
                    ->where('is_core', 1)
                    ->where('is_active', 1)
                    ->orderBy('code', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get elective subjects for a curriculum
     */
    public function getElectiveSubjectsByCurriculum($curriculumId)
    {
        return $this->where('curriculum_id', $curriculumId)
                    ->where('is_core', 0)
                    ->where('is_active', 1)
                    ->orderBy('code', 'ASC')
                    ->findAll();
    }
}
