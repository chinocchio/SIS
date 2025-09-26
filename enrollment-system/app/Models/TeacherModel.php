<?php

namespace App\Models;

use CodeIgniter\Model;

class TeacherModel extends Model
{
    protected $table = 'teachers';
    protected $primaryKey = 'id';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'first_name', 'last_name', 'email', 'username', 'password', 'specialization', 'is_active'
    ];
    
    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[teachers.email,id,{id}]',
        'username' => 'required|min_length[3]|max_length[100]|is_unique[teachers.username,id,{id}]',
        'password' => 'required|min_length[6]'
    ];
    
    protected $validationMessages = [
        'first_name' => [
            'required' => 'First name is required',
            'min_length' => 'First name must be at least 2 characters',
            'max_length' => 'First name must not exceed 100 characters'
        ],
        'last_name' => [
            'required' => 'Last name is required',
            'min_length' => 'Last name must be at least 2 characters',
            'max_length' => 'Last name must not exceed 100 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'Email already exists in the system'
        ],
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must be at least 3 characters',
            'max_length' => 'Username must not exceed 100 characters',
            'is_unique' => 'Username already exists in the system'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters'
        ]
    ];
    
    protected $useTimestamps = true;
    
    public function getTeachersWithAssignments()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('teachers t')
                    ->select('t.*, 
                             COUNT(tsa.id) as assignment_count,
                             GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR ", ") as subjects,
                             GROUP_CONCAT(DISTINCT sec.name ORDER BY sec.name SEPARATOR ", ") as sections')
                    ->join('teacher_subject_assignments tsa', 'tsa.teacher_id = t.id AND tsa.is_active = 1', 'left')
                    ->join('subjects s', 's.id = tsa.subject_id', 'left')
                    ->join('sections sec', 'sec.id = tsa.section_id', 'left')
                    ->groupBy('t.id')
                    ->orderBy('t.last_name', 'ASC')
                    ->get();
        
        return $query->getResultArray();
    }
    
    public function getTeacherWithAssignments($teacherId)
    {
        $db = \Config\Database::connect();
        
        // Get teacher basic info
        $teacher = $this->find($teacherId);
        if (!$teacher) {
            return null;
        }
        
        // Get subject assignments
        $assignments = $db->table('teacher_subject_assignments tsa')
                         ->select('tsa.*, s.name as subject_name, s.code as subject_code,
                                  sec.name as section_name, sec.grade_level as section_grade_level,
                                  sy.name as school_year')
                         ->join('subjects s', 's.id = tsa.subject_id')
                         ->join('sections sec', 'sec.id = tsa.section_id')
                         ->join('school_years sy', 'sy.id = tsa.school_year_id')
                         ->where('tsa.teacher_id', $teacherId)
                         ->where('tsa.is_active', 1)
                         ->orderBy('sy.name', 'DESC')
                         ->orderBy('sec.grade_level', 'ASC')
                         ->orderBy('s.name', 'ASC')
                         ->get()
                         ->getResultArray();
        
        $teacher['assignments'] = $assignments;
        return $teacher;
    }
    
    public function getAvailableSubjects($teacherId = null)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('subjects s')
                    ->select('s.*, c.name as curriculum_name')
                    ->join('curriculums c', 'c.id = s.curriculum_id', 'left')
                    ->where('s.is_active', 1)
                    ->orderBy('s.name', 'ASC');
        
        // If teacher ID provided, exclude subjects already assigned to this teacher
        if ($teacherId) {
            $query->whereNotIn('s.id', function($builder) use ($teacherId) {
                return $builder->select('subject_id')
                              ->from('teacher_subject_assignments')
                              ->where('teacher_id', $teacherId)
                              ->where('is_active', 1);
            });
        }
        
        return $query->get()->getResultArray();
    }
    
    public function assignSubjectToTeacher($teacherId, $subjectId, $sectionId, $schoolYearId)
    {
        $db = \Config\Database::connect();
        
        // Get the subject details to find all quarters for this subject
        $subject = $db->table('subjects')->where('id', $subjectId)->get()->getRowArray();
        if (!$subject) {
            return false;
        }
        
        // Find all subject records for the same subject name/code and grade level
        $allSubjectRecords = $db->table('subjects')
                              ->where('name', $subject['name'])
                              ->where('grade_level', $subject['grade_level'])
                              ->where('is_active', 1)
                              ->get()
                              ->getResultArray();
        
        $assignmentsCreated = 0;
        $assignmentsSkipped = 0;
        
        // Assign teacher to all quarters of this subject
        foreach ($allSubjectRecords as $subjectRecord) {
            // Check if assignment already exists for this specific quarter
            $existing = $db->table('teacher_subject_assignments')
                          ->where('teacher_id', $teacherId)
                          ->where('subject_id', $subjectRecord['id'])
                          ->where('section_id', $sectionId)
                          ->where('school_year_id', $schoolYearId)
                          ->get()
                          ->getRowArray();
            
            if ($existing) {
                // Reactivate if exists but inactive
                if (!$existing['is_active']) {
                    $db->table('teacher_subject_assignments')
                       ->where('id', $existing['id'])
                       ->update(['is_active' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                    $assignmentsCreated++;
                } else {
                    $assignmentsSkipped++;
                }
            } else {
                // Create new assignment for this quarter
                $data = [
                    'teacher_id' => $teacherId,
                    'subject_id' => $subjectRecord['id'],
                    'section_id' => $sectionId,
                    'school_year_id' => $schoolYearId,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                if ($db->table('teacher_subject_assignments')->insert($data)) {
                    $assignmentsCreated++;
                }
            }
        }
        
        // Return true if at least one assignment was created or reactivated
        return $assignmentsCreated > 0;
    }
    
    public function removeSubjectAssignment($assignmentId)
    {
        $db = \Config\Database::connect();
        
        // Get the assignment details
        $assignment = $db->table('teacher_subject_assignments')
                        ->where('id', $assignmentId)
                        ->get()
                        ->getRowArray();
        
        if (!$assignment) {
            return false;
        }
        
        // Get the subject details to find all quarters
        $subject = $db->table('subjects')->where('id', $assignment['subject_id'])->get()->getRowArray();
        if (!$subject) {
            return false;
        }
        
        // Find all subject records for the same subject name/code and grade level
        $allSubjectRecords = $db->table('subjects')
                              ->where('name', $subject['name'])
                              ->where('grade_level', $subject['grade_level'])
                              ->where('is_active', 1)
                              ->get()
                              ->getResultArray();
        
        $removedCount = 0;
        
        // Remove teacher from all quarters of this subject
        foreach ($allSubjectRecords as $subjectRecord) {
            $result = $db->table('teacher_subject_assignments')
                        ->where('teacher_id', $assignment['teacher_id'])
                        ->where('subject_id', $subjectRecord['id'])
                        ->where('section_id', $assignment['section_id'])
                        ->where('school_year_id', $assignment['school_year_id'])
                        ->update(['is_active' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
            
            if ($result) {
                $removedCount++;
            }
        }
        
        return $removedCount > 0;
    }
    
    public function getTeachersBySubject($subjectId)
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('teacher_subject_assignments tsa')
                    ->select('t.*, sec.name as section_name, sy.name as school_year')
                    ->join('teachers t', 't.id = tsa.teacher_id')
                    ->join('sections sec', 'sec.id = tsa.section_id')
                    ->join('school_years sy', 'sy.id = tsa.school_year_id')
                    ->where('tsa.subject_id', $subjectId)
                    ->where('tsa.is_active', 1)
                    ->orderBy('t.last_name', 'ASC')
                    ->get();
        
        return $query->getResultArray();
    }
    
    public function getAllAssignments()
    {
        $db = \Config\Database::connect();
        
        $query = $db->table('teacher_subject_assignments tsa')
                    ->select('tsa.*, 
                             CONCAT(t.first_name, " ", t.last_name) as teacher_name,
                             t.specialization as teacher_specialization,
                             s.name as subject_name, s.code as subject_code,
                             sec.name as section_name, sec.grade_level as section_grade_level,
                             sy.name as school_year')
                    ->join('teachers t', 't.id = tsa.teacher_id')
                    ->join('subjects s', 's.id = tsa.subject_id')
                    ->join('sections sec', 'sec.id = tsa.section_id')
                    ->join('school_years sy', 'sy.id = tsa.school_year_id')
                    ->where('tsa.is_active', 1)
                    ->orderBy('sy.name', 'DESC')
                    ->orderBy('sec.grade_level', 'ASC')
                    ->orderBy('t.last_name', 'ASC')
                    ->orderBy('s.name', 'ASC')
                    ->get();
        
        return $query->getResultArray();
    }
}
