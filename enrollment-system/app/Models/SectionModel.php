<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $table = 'sections';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'name', 'grade_level', 'strand_id', 'school_year_id', 
        'capacity_min', 'capacity_max'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'name' => 'required|max_length[20]',
        'grade_level' => 'required|integer|greater_than[0]|less_than[13]',
        'school_year_id' => 'required|integer',
        'capacity_min' => 'permit_empty|integer|greater_than[0]',
        'capacity_max' => 'permit_empty|integer|greater_than[0]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Section name is required',
            'max_length' => 'Section name cannot exceed 20 characters'
        ],
        'grade_level' => [
            'required' => 'Grade level is required',
            'integer' => 'Grade level must be a number',
            'greater_than' => 'Grade level must be between 1 and 12',
            'less_than' => 'Grade level must be between 1 and 12'
        ],
        'school_year_id' => [
            'required' => 'School year is required',
            'integer' => 'School year must be a number'
        ],
        'capacity_min' => [
            'integer' => 'Minimum capacity must be a number',
            'greater_than' => 'Minimum capacity must be greater than 0'
        ],
        'capacity_max' => [
            'integer' => 'Maximum capacity must be a number',
            'greater_than' => 'Maximum capacity must be greater than 0'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Relationships
    public function getSectionWithDetails($id = null)
    {
        $builder = $this->db->table('sections s');
        $builder->select('s.*, sy.name as school_year, st.name as strand_name');
        $builder->join('school_years sy', 'sy.id = s.school_year_id', 'left');
        $builder->join('strands st', 'st.id = s.strand_id', 'left');
        
        if ($id) {
            $builder->where('s.id', $id);
            return $builder->get()->getRowArray();
        }
        
        return $builder->get()->getResultArray();
    }

    public function getSectionsBySchoolYear($schoolYearId)
    {
        return $this->where('school_year_id', $schoolYearId)
                   ->orderBy('grade_level', 'ASC')
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    public function getSectionsByGradeLevel($gradeLevel, $schoolYearId = null)
    {
        $query = $this->where('grade_level', $gradeLevel);
        
        if ($schoolYearId) {
            $query = $query->where('school_year_id', $schoolYearId);
        }
        
        return $query->orderBy('name', 'ASC')->findAll();
    }

    public function getSectionsByStrand($strandId, $schoolYearId = null)
    {
        $query = $this->where('strand_id', $strandId);
        
        if ($schoolYearId) {
            $query = $query->where('school_year_id', $schoolYearId);
        }
        
        return $query->orderBy('grade_level', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getAvailableSections($gradeLevel, $strandId = null, $schoolYearId = null)
    {
        $query = $this->where('grade_level', $gradeLevel);
        
        if ($strandId) {
            $query = $query->where('strand_id', $strandId);
        }
        
        if ($schoolYearId) {
            $query = $query->where('school_year_id', $schoolYearId);
        }
        
        return $query->orderBy('name', 'ASC')->findAll();
    }

    public function getSectionCapacity($sectionId)
    {
        $section = $this->find($sectionId);
        if (!$section) {
            return ['current' => 0, 'min' => 0, 'max' => 0];
        }

        // Count current students in this section
        $currentCount = $this->db->table('students')
                                ->where('section_id', $sectionId)
                                ->countAllResults();

        return [
            'current' => $currentCount,
            'min' => $section['capacity_min'] ?? 35,
            'max' => $section['capacity_max'] ?? 40
        ];
    }

    public function isNameUniqueInSchoolYear($name, $schoolYearId, $excludeId = null)
    {
        $query = $this->where('name', $name)
                      ->where('school_year_id', $schoolYearId);
        
        if ($excludeId) {
            $query = $query->where('id !=', $excludeId);
        }
        
        return $query->countAllResults() === 0;
    }

    public function getSectionsSummary($schoolYearId = null)
    {
        $builder = $this->db->table('sections s');
        $builder->select('s.*, sy.name as school_year, st.name as strand_name, COUNT(stu.id) as student_count');
        $builder->join('school_years sy', 'sy.id = s.school_year_id', 'left');
        $builder->join('strands st', 'st.id = s.strand_id', 'left');
        $builder->join('students stu', 'stu.section_id = s.id', 'left');
        
        if ($schoolYearId) {
            $builder->where('s.school_year_id', $schoolYearId);
        }
        
        $builder->groupBy('s.id');
        $builder->orderBy('s.grade_level', 'ASC');
        $builder->orderBy('s.name', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
