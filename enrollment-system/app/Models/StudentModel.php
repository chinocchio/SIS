<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'lrn', 'first_name', 'last_name', 'middle_name', 'full_name', 'email', 'password',
        'birth_date', 'gender', 'grade_level', 'previous_grade_level', 
        'admission_type', 'enrollment_type', 'strand_id', 'curriculum_id', 
        'previous_school', 'status'
    ];
    
    protected $validationRules = [
        'lrn' => 'required|min_length[12]|max_length[12]|is_unique[students.lrn,id,{id}]',
        'full_name' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email|is_unique[students.email,id,{id}]',
        'password' => 'required|min_length[6]'
        // Removed strict validation for other fields to allow minimal student creation
    ];
    
    protected $validationMessages = [
        'lrn' => [
            'required' => 'LRN is required',
            'min_length' => 'LRN must be exactly 12 digits',
            'max_length' => 'LRN must be exactly 12 digits',
            'is_unique' => 'LRN already exists in the system'
        ],
        'full_name' => [
            'required' => 'Full name is required',
            'min_length' => 'Full name must be at least 2 characters',
            'max_length' => 'Full name must not exceed 255 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email already exists in the system'
        ]
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
    
    // ==================== STUDENT MANAGEMENT METHODS ====================
    
    public function getAllStudentsWithPagination($perPage = 20)
    {
        $students = $this->orderBy('created_at', 'DESC')
                        ->paginate($perPage);
        
        return [
            'students' => $students,
            'pager' => $this->pager
        ];
    }
    
    public function searchStudents($searchTerm)
    {
        return $this->like('full_name', $searchTerm)
                    ->orLike('lrn', $searchTerm)
                    ->orLike('email', $searchTerm)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    
    public function getStudentByLRN($lrn)
    {
        return $this->where('lrn', $lrn)->first();
    }
    
    public function getStudentsByStatus($status)
    {
        return $this->where('status', $status)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }
    
    public function getStudentsByGradeLevel($gradeLevel)
    {
        return $this->where('grade_level', $gradeLevel)
                    ->where('status', 'approved')
                    ->orderBy('full_name', 'ASC')
                    ->findAll();
    }
    
    public function getStudentWithDetails($studentId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('students s')
                    ->select('s.*, st.name as strand_name, c.name as curriculum_name')
                    ->join('strands st', 'st.id = s.strand_id', 'left')
                    ->join('curriculums c', 'c.id = s.curriculum_id', 'left')
                    ->where('s.id', $studentId)
                    ->get();
        
        $student = $query->getRowArray();
        
        // Get subjects for the student based on their curriculum or strand
        if ($student) {
            $subjectModel = new \App\Models\SubjectModel();
            
            if ($student['curriculum_id']) {
                // JHS student - get all subjects for their curriculum across all grade levels (7-10)
                $student['subjects'] = $subjectModel->getSubjectsByCurriculum($student['curriculum_id']);
            } elseif ($student['strand_id']) {
                // SHS student - get subjects for both Grade 11 and Grade 12 for their strand
                $grade11Subjects = $subjectModel->getSubjectsByGradeAndStrand(11, $student['strand_id']);
                $grade12Subjects = $subjectModel->getSubjectsByGradeAndStrand(12, $student['strand_id']);
                
                // Combine both grade levels' subjects
                $student['subjects'] = array_merge($grade11Subjects, $grade12Subjects);
            } else {
                $student['subjects'] = [];
            }
        } else {
            $student['subjects'] = [];
        }
        
        return $student;
    }
    
    public function isLrnUnique($lrn, $excludeId = null)
    {
        $query = $this->where('lrn', $lrn);
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        return $query->countAllResults() === 0;
    }
    
    public function getStudentsByGradeAndStatus($gradeLevel, $status = null)
    {
        $query = $this->where('grade_level', $gradeLevel);
        if ($status) {
            $query->where('status', $status);
        }
        return $query->orderBy('full_name', 'ASC')->findAll();
    }
    
    public function updateStudentStatus($studentId, $status)
    {
        if (!in_array($status, ['draft', 'pending', 'approved', 'rejected'])) {
            return false;
        }
        return $this->update($studentId, ['status' => $status]);
    }
}
