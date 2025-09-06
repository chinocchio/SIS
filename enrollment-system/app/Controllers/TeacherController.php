<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\SectionModel;
use App\Models\SchoolYearModel;
use App\Models\TeacherModel;

class TeacherController extends BaseController
{
    public function index()
    {
        $teacherId = session()->get('user_id');
        $teacherModel = new TeacherModel();
        $studentModel = new StudentModel();
        $schoolYearModel = new SchoolYearModel();
        
        // Get teacher's assigned subjects and sections
        $assignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        // Get students for each assigned section
        $sectionsWithStudents = [];
        if (!empty($assignments['assignments'])) {
            foreach ($assignments['assignments'] as $assignment) {
                $sectionId = $assignment['section_id'];
                $students = $studentModel->getStudentsBySection($sectionId);
                
                $sectionsWithStudents[] = [
                    'assignment' => $assignment,
                    'students' => $students,
                    'student_count' => count($students)
                ];
            }
        }
        
        $data = [
            'teacher' => $assignments,
            'sectionsWithStudents' => $sectionsWithStudents,
            'activeSchoolYear' => $activeSchoolYear,
            'totalAssignments' => count($assignments['assignments'] ?? []),
            'totalStudents' => array_sum(array_column($sectionsWithStudents, 'student_count'))
        ];
        
        return view('teacher/dashboard', $data);
    }
    
    public function viewStudents($sectionId)
    {
        $teacherId = session()->get('user_id');
        $sectionModel = new SectionModel();
        $studentModel = new StudentModel();
        $teacherModel = new TeacherModel();
        
        // Verify teacher is assigned to this section
        $teacherAssignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $isAssigned = false;
        $subjectInfo = null;
        
        if (!empty($teacherAssignments['assignments'])) {
            foreach ($teacherAssignments['assignments'] as $assignment) {
                if ($assignment['section_id'] == $sectionId) {
                    $isAssigned = true;
                    $subjectInfo = $assignment;
                    break;
                }
            }
        }
        
        if (!$isAssigned) {
            return redirect()->to('/teacher/dashboard')->with('error', 'You are not assigned to this section.');
        }
        
        $section = $sectionModel->find($sectionId);
        $students = $studentModel->getStudentsBySection($sectionId);
        
        $data = [
            'section' => $section,
            'students' => $students,
            'subjectInfo' => $subjectInfo
        ];
        
        return view('teacher/view_students', $data);
    }
    
    public function inputGrades($sectionId)
    {
        $teacherId = session()->get('user_id');
        $sectionModel = new SectionModel();
        $studentModel = new StudentModel();
        $teacherModel = new TeacherModel();
        $schoolYearModel = new SchoolYearModel();
        
        // Verify teacher is assigned to this section
        $teacherAssignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $isAssigned = false;
        $subjectInfo = null;
        
        if (!empty($teacherAssignments['assignments'])) {
            foreach ($teacherAssignments['assignments'] as $assignment) {
                if ($assignment['section_id'] == $sectionId) {
                    $isAssigned = true;
                    $subjectInfo = $assignment;
                    break;
                }
            }
        }
        
        if (!$isAssigned) {
            return redirect()->to('/teacher/dashboard')->with('error', 'You are not assigned to this section.');
        }
        
        $section = $sectionModel->find($sectionId);
        $students = $studentModel->getStudentsBySection($sectionId);
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        // Get existing grades for this subject and section
        $db = \Config\Database::connect();
        $existingGrades = $db->table('student_grades sg')
                            ->select('sg.*, s.full_name as student_name')
                            ->join('students s', 's.id = sg.student_id')
                            ->where('sg.subject_id', $subjectInfo['subject_id'])
                            ->where('sg.school_year_id', $activeSchoolYear['id'])
                            ->whereIn('sg.student_id', array_column($students, 'id'))
                            ->get()
                            ->getResultArray();
        
        // Create grades lookup array
        $gradesLookup = [];
        foreach ($existingGrades as $grade) {
            $gradesLookup[$grade['student_id']] = $grade['grade'];
        }
        
        $data = [
            'section' => $section,
            'students' => $students,
            'subjectInfo' => $subjectInfo,
            'activeSchoolYear' => $activeSchoolYear,
            'gradesLookup' => $gradesLookup
        ];
        
        return view('teacher/input_grades', $data);
    }
    
    public function saveGrades()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        
        $teacherId = session()->get('user_id');
        $subjectId = $this->request->getPost('subject_id');
        $schoolYearId = $this->request->getPost('school_year_id');
        $sectionId = $this->request->getPost('section_id');
        $grades = $this->request->getPost('grades');
        
        // Verify teacher is assigned to this subject and section
        $teacherModel = new TeacherModel();
        $teacherAssignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $isAssigned = false;
        
        if (!empty($teacherAssignments['assignments'])) {
            foreach ($teacherAssignments['assignments'] as $assignment) {
                if ($assignment['section_id'] == $sectionId && $assignment['subject_id'] == $subjectId) {
                    $isAssigned = true;
                    break;
                }
            }
        }
        
        if (!$isAssigned) {
            return redirect()->back()->with('error', 'You are not assigned to this subject and section.');
        }
        
        $db = \Config\Database::connect();
        $savedCount = 0;
        $updatedCount = 0;
        
        foreach ($grades as $studentId => $grade) {
            if ($grade !== '' && is_numeric($grade)) {
                $grade = floatval($grade);
                
                // Validate grade (0-100)
                if ($grade < 0 || $grade > 100) {
                    continue; // Skip invalid grades
                }
                
                // Check if grade already exists
                $existingGrade = $db->table('student_grades')
                                   ->where('student_id', $studentId)
                                   ->where('subject_id', $subjectId)
                                   ->where('school_year_id', $schoolYearId)
                                   ->get()
                                   ->getRowArray();
                
                if ($existingGrade) {
                    // Update existing grade
                    $db->table('student_grades')
                       ->where('id', $existingGrade['id'])
                       ->update(['grade' => $grade, 'updated_at' => date('Y-m-d H:i:s')]);
                    $updatedCount++;
                } else {
                    // Insert new grade
                    $db->table('student_grades')->insert([
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'school_year_id' => $schoolYearId,
                        'grade' => $grade,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $savedCount++;
                }
            }
        }
        
        $message = "Grades saved successfully! {$savedCount} new grades added, {$updatedCount} grades updated.";
        return redirect()->to('/teacher/grades/' . $sectionId)->with('success', $message);
    }
    
    public function viewGrades($studentId, $schoolYearId)
    {
        $studentModel = new StudentModel();
        $schoolYearModel = new SchoolYearModel();
        
        $student = $studentModel->find($studentId);
        $schoolYear = $schoolYearModel->find($schoolYearId);
        
        // Get student grades for the school year
        $db = \Config\Database::connect();
        $grades = $db->table('student_grades sg')
                    ->join('subjects s', 's.id = sg.subject_id')
                    ->where('sg.student_id', $studentId)
                    ->where('sg.school_year_id', $schoolYearId)
                    ->get()
                    ->getResultArray();
        
        $data = [
            'student' => $student,
            'schoolYear' => $schoolYear,
            'grades' => $grades
        ];
        
        return view('teacher/view_grades', $data);
    }
    
    public function generateReportCard($studentId, $schoolYearId)
    {
        $studentModel = new StudentModel();
        $schoolYearModel = new SchoolYearModel();
        
        $student = $studentModel->find($studentId);
        $schoolYear = $schoolYearModel->find($schoolYearId);
        
        // Get all grades and calculate averages
        $db = \Config\Database::connect();
        $grades = $db->table('student_grades sg')
                    ->join('subjects s', 's.id = sg.subject_id')
                    ->where('sg.student_id', $studentId)
                    ->where('sg.school_year_id', $schoolYearId)
                    ->get()
                    ->getResultArray();
        
        // Calculate general average
        $totalGrade = 0;
        $subjectCount = count($grades);
        
        foreach ($grades as $grade) {
            $totalGrade += $grade['grade'];
        }
        
        $generalAverage = $subjectCount > 0 ? round($totalGrade / $subjectCount, 2) : 0;
        
        // Determine if student passed (75 and above)
        $passed = $generalAverage >= 75;
        
        $data = [
            'student' => $student,
            'schoolYear' => $schoolYear,
            'grades' => $grades,
            'generalAverage' => $generalAverage,
            'passed' => $passed
        ];
        
        return view('teacher/report_card', $data);
    }
    
    public function gradeManagement()
    {
        $teacherId = session()->get('user_id');
        $teacherModel = new TeacherModel();
        $schoolYearModel = new SchoolYearModel();
        
        // Get teacher's assigned subjects and sections
        $assignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        $data = [
            'assignments' => $assignments['assignments'] ?? [],
            'activeSchoolYear' => $activeSchoolYear
        ];
        
        return view('teacher/grade_management', $data);
    }
    
    public function reports()
    {
        $teacherId = session()->get('user_id');
        $teacherModel = new TeacherModel();
        $schoolYearModel = new SchoolYearModel();
        
        // Get teacher's assigned subjects and sections
        $assignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        $data = [
            'assignments' => $assignments['assignments'] ?? [],
            'activeSchoolYear' => $activeSchoolYear
        ];
        
        return view('teacher/reports', $data);
    }
}
