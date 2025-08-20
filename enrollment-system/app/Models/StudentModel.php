<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'first_name','last_name','email','password',
        'grade_level','previous_grade_level','admission_type','strand_id','status'
    ];
    
    protected $useTimestamps = true;
    
    public function determineAdmissionType($gradeLevel, $previousGradeLevel = null)
    {
        if ($previousGradeLevel === null) {
            // New student
            if ($gradeLevel > 7) {
                return 'transferee';
            }
            return 'regular';
        } else {
            // Existing student
            if ($gradeLevel > $previousGradeLevel) {
                return 'promoted';
            } else if ($gradeLevel == $previousGradeLevel) {
                return 're-enroll';
            } else {
                return 'transferee';
            }
        }
    }
    
    public function canPromoteToNextGrade($studentId, $schoolYearId)
    {
        // Check if student has failing grades (< 75)
        $db = \Config\Database::connect();
        
        $query = $db->table('student_grades sg')
                    ->join('subjects s', 's.id = sg.subject_id')
                    ->where('sg.student_id', $studentId)
                    ->where('sg.school_year_id', $schoolYearId)
                    ->where('sg.grade <', 75)
                    ->get();
        
        return $query->getNumRows() === 0;
    }
    
    public function promoteStudentsToNextGrade($schoolYearId)
    {
        $db = \Config\Database::connect();
        
        // Get all students who can be promoted
        $students = $this->where('status', 'approved')->findAll();
        
        foreach ($students as $student) {
            if ($this->canPromoteToNextGrade($student['id'], $schoolYearId)) {
                $newGradeLevel = $student['grade_level'] + 1;
                
                // Don't promote beyond Grade 12
                if ($newGradeLevel <= 12) {
                    $this->update($student['id'], [
                        'previous_grade_level' => $student['grade_level'],
                        'grade_level' => $newGradeLevel,
                        'admission_type' => 'promoted'
                    ]);
                }
            }
        }
    }
}
