<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'curriculum_id', 'strand_id', 'code', 'name', 'description', 'units', 
        'grade_level', 'semester', 'quarter', 'is_core', 'is_active'
    ];
    
    protected $validationRules = [
        'curriculum_id' => 'permit_empty|integer',
        'strand_id' => 'permit_empty|integer',
        'code' => 'required|min_length[2]|max_length[20]',
        'name' => 'required|min_length[2]|max_length[100]',
        'units' => 'permit_empty|numeric|greater_than[0]|less_than[10]',
        'grade_level' => 'required|integer|greater_than[6]|less_than[13]',
        'semester' => 'permit_empty|integer|greater_than[0]|less_than[3]',
        'quarter' => 'required|integer|greater_than[0]|less_than[5]'
    ];
    
    protected $validationMessages = [
        'curriculum_id' => [
            'integer' => 'Curriculum must be a valid selection'
        ],
        'strand_id' => [
            'integer' => 'Strand must be a valid selection'
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
        ],
        'grade_level' => [
            'required' => 'Grade level is required',
            'integer' => 'Grade level must be a number',
            'greater_than' => 'Grade level must be between 7-12',
            'less_than' => 'Grade level must be between 7-12'
        ],
        'semester' => [
            'integer' => 'Semester must be a number',
            'greater_than' => 'Semester must be 1 or 2',
            'less_than' => 'Semester must be 1 or 2'
        ],
        'quarter' => [
            'required' => 'Quarter is required',
            'integer' => 'Quarter must be a number',
            'greater_than' => 'Quarter must be between 1-4',
            'less_than' => 'Quarter must be between 1-4'
        ]
    ];
    
    protected $useTimestamps = true;
    
    /**
     * Get all subjects for a curriculum across all grade levels and quarters
     */
    public function getSubjectsByCurriculum($curriculumId)
    {
        return $this->where('curriculum_id', $curriculumId)
                    ->where('is_active', 1)
                    ->orderBy('grade_level', 'ASC')
                    ->orderBy('quarter', 'ASC')
                    ->orderBy('name', 'ASC')
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
     * Check if subject code is unique within a curriculum/strand and grade/semester/quarter
     */
    public function isCodeUniqueInCurriculum($code, $curriculumId, $gradeLevel, $semester, $quarter, $excludeId = null)
    {
        $query = $this->where('code', $code)
                      ->where('curriculum_id', $curriculumId)
                      ->where('grade_level', $gradeLevel)
                      ->where('quarter', $quarter);
        
        if ($semester !== null) {
            $query->where('semester', $semester);
        } else {
            $query->where('semester IS NULL');
        }
        
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        
        return $query->countAllResults() === 0;
    }
    
    /**
     * Check if subject code is unique within a strand and grade/semester/quarter
     */
    public function isCodeUniqueInStrand($code, $strandId, $gradeLevel, $semester, $quarter, $excludeId = null)
    {
        $query = $this->where('code', $code)
                      ->where('strand_id', $strandId)
                      ->where('grade_level', $gradeLevel)
                      ->where('quarter', $quarter);
        
        if ($semester !== null) {
            $query->where('semester', $semester);
        } else {
            $query->where('semester IS NULL');
        }
        
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
    
    /**
     * Get subjects for a specific grade level and curriculum
     */
    public function getSubjectsByGradeAndCurriculum($gradeLevel, $curriculumId)
    {
        return $this->where('curriculum_id', $curriculumId)
                    ->where('grade_level', $gradeLevel)
                    ->where('is_active', 1)
                    ->orderBy('quarter', 'ASC')
                    ->orderBy('code', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get subjects for a specific grade level and strand
     */
    public function getSubjectsByGradeAndStrand($gradeLevel, $strandId)
    {
        return $this->where('strand_id', $strandId)
                    ->where('grade_level', $gradeLevel)
                    ->where('is_active', 1)
                    ->orderBy('semester', 'ASC')
                    ->orderBy('quarter', 'ASC')
                    ->orderBy('code', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get subjects for a specific grade level, semester, and strand (SHS)
     */
    public function getSubjectsByGradeSemesterAndStrand($gradeLevel, $semester, $strandId)
    {
        return $this->where('strand_id', $strandId)
                    ->where('grade_level', $gradeLevel)
                    ->where('semester', $semester)
                    ->where('is_active', 1)
                    ->orderBy('quarter', 'ASC')
                    ->orderBy('code', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get all subjects for a student based on their curriculum/strand and grade level
     */
    public function getSubjectsForStudent($studentData)
    {
        if (isset($studentData['curriculum_id']) && $studentData['curriculum_id']) {
            // JHS Student - get subjects by curriculum and grade level
            return $this->getSubjectsByGradeAndCurriculum(
                $studentData['grade_level'], 
                $studentData['curriculum_id']
            );
        } elseif (isset($studentData['strand_id']) && $studentData['strand_id']) {
            // SHS Student - get subjects by strand and grade level
            return $this->getSubjectsByGradeAndStrand(
                $studentData['grade_level'], 
                $studentData['strand_id']
            );
        }
        
        return [];
    }
    
    /**
     * Get future subjects for a student (next grade level)
     */
    public function getFutureSubjectsForStudent($studentData)
    {
        $nextGrade = $studentData['grade_level'] + 1;
        
        if ($nextGrade > 12) {
            return []; // Student is already in highest grade
        }
        
        if (isset($studentData['curriculum_id']) && $studentData['curriculum_id']) {
            // JHS Student - get next grade subjects
            return $this->getSubjectsByGradeAndCurriculum(
                $nextGrade, 
                $studentData['curriculum_id']
            );
        } elseif (isset($studentData['strand_id']) && $studentData['strand_id']) {
            // SHS Student - get next grade subjects
            return $this->getSubjectsByGradeAndStrand(
                $nextGrade, 
                $studentData['strand_id']
            );
        }
        
        return [];
    }
}
