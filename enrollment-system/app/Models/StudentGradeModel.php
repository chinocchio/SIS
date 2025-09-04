<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentGradeModel extends Model
{
    protected $table = 'student_grades';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'student_id', 'subject_id', 'school_year_id', 'quarter', 'grade', 'remarks', 'is_final'
    ];
    
    protected $validationRules = [
        'student_id' => 'required|integer',
        'subject_id' => 'required|integer',
        'school_year_id' => 'required|integer',
        'quarter' => 'required|integer|greater_than[0]|less_than[5]',
        'grade' => 'permit_empty|numeric|greater_than[0]|less_than[101]',
        'remarks' => 'permit_empty|max_length[50]',
        'is_final' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'student_id' => [
            'required' => 'Student is required',
            'integer' => 'Student must be a valid selection'
        ],
        'subject_id' => [
            'required' => 'Subject is required',
            'integer' => 'Subject must be a valid selection'
        ],
        'school_year_id' => [
            'required' => 'School year is required',
            'integer' => 'School year must be a valid selection'
        ],
        'quarter' => [
            'required' => 'Quarter is required',
            'integer' => 'Quarter must be a number',
            'greater_than' => 'Quarter must be between 1-4',
            'less_than' => 'Quarter must be between 1-4'
        ],
        'grade' => [
            'numeric' => 'Grade must be a number',
            'greater_than' => 'Grade must be greater than 0',
            'less_than' => 'Grade must be less than 101'
        ],
        'remarks' => [
            'max_length' => 'Remarks must not exceed 50 characters'
        ],
        'is_final' => [
            'in_list' => 'Final status must be 0 or 1'
        ]
    ];
    
    protected $useTimestamps = true;
    
    /**
     * Get all grades for a specific student
     */
    public function getGradesByStudent($studentId, $schoolYearId = null)
    {
        $query = $this->where('student_id', $studentId);
        
        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }
        
        return $query->orderBy('school_year_id', 'DESC')
                    ->orderBy('quarter', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get grades for a specific student and subject
     */
    public function getGradesByStudentAndSubject($studentId, $subjectId, $schoolYearId = null)
    {
        $query = $this->where('student_id', $studentId)
                      ->where('subject_id', $subjectId);
        
        if ($schoolYearId) {
            $query->where('school_year_id', $schoolYearId);
        }
        
        return $query->orderBy('school_year_id', 'DESC')
                    ->orderBy('quarter', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get grades for a specific student, subject, and school year
     */
    public function getGradesByStudentSubjectAndYear($studentId, $subjectId, $schoolYearId)
    {
        return $this->where('student_id', $studentId)
                    ->where('subject_id', $subjectId)
                    ->where('school_year_id', $schoolYearId)
                    ->orderBy('quarter', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get grade for a specific student, subject, school year, and quarter
     */
    public function getGradeByStudentSubjectYearAndQuarter($studentId, $subjectId, $schoolYearId, $quarter)
    {
        return $this->where('student_id', $studentId)
                    ->where('subject_id', $subjectId)
                    ->where('school_year_id', $schoolYearId)
                    ->where('quarter', $quarter)
                    ->first();
    }
    
    /**
     * Get all grades for a specific school year
     */
    public function getGradesBySchoolYear($schoolYearId)
    {
        return $this->where('school_year_id', $schoolYearId)
                    ->orderBy('student_id', 'ASC')
                    ->orderBy('quarter', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get grades summary for a student (averages per subject)
     */
    public function getGradesSummaryByStudent($studentId, $schoolYearId = null)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('student_grades sg')
                    ->select('sg.subject_id, s.name as subject_name, s.code as subject_code, 
                             AVG(sg.grade) as average_grade, COUNT(sg.grade) as quarters_completed,
                             MAX(sg.grade) as highest_grade, MIN(sg.grade) as lowest_grade')
                    ->join('subjects s', 's.id = sg.subject_id')
                    ->where('sg.student_id', $studentId)
                    ->where('sg.grade IS NOT NULL');
        
        if ($schoolYearId) {
            $query->where('sg.school_year_id', $schoolYearId);
        }
        
        return $query->groupBy('sg.subject_id')
                    ->orderBy('s.code', 'ASC')
                    ->get()
                    ->getResultArray();
    }
    
    /**
     * Check if a grade exists for a specific student, subject, school year, and quarter
     */
    public function gradeExists($studentId, $subjectId, $schoolYearId, $quarter)
    {
        return $this->where('student_id', $studentId)
                    ->where('subject_id', $subjectId)
                    ->where('school_year_id', $schoolYearId)
                    ->where('quarter', $quarter)
                    ->countAllResults() > 0;
    }
    
    /**
     * Update or create a grade
     */
    public function updateOrCreateGrade($data)
    {
        $existingGrade = $this->getGradeByStudentSubjectYearAndQuarter(
            $data['student_id'], 
            $data['subject_id'], 
            $data['school_year_id'], 
            $data['quarter']
        );
        
        if ($existingGrade) {
            // Update existing grade
            return $this->update($existingGrade['id'], $data);
        } else {
            // Create new grade
            return $this->insert($data);
        }
    }
}
