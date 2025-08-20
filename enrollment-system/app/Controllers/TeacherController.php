<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\SubjectModel;
use App\Models\SectionModel;
use App\Models\SchoolYearModel;

class TeacherController extends BaseController
{
    public function index()
    {
        // Teacher dashboard - show assigned subjects and sections
        return view('teacher/dashboard');
    }
    
    public function viewStudents($sectionId)
    {
        $sectionModel = new SectionModel();
        $studentModel = new StudentModel();
        
        $section = $sectionModel->find($sectionId);
        $students = $studentModel->getStudentsBySection($sectionId);
        
        $data = [
            'section' => $section,
            'students' => $students
        ];
        
        return view('teacher/view_students', $data);
    }
    
    public function inputGrades()
    {
        if ($this->request->getMethod() === 'post') {
            $studentId = $this->request->getPost('student_id');
            $subjectId = $this->request->getPost('subject_id');
            $schoolYearId = $this->request->getPost('school_year_id');
            $grade = $this->request->getPost('grade');
            
            // Validate grade (0-100)
            if ($grade < 0 || $grade > 100) {
                return redirect()->back()->with('error', 'Grade must be between 0 and 100.');
            }
            
            // Check if grade already exists
            $db = \Config\Database::connect();
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
                $message = 'Grade updated successfully.';
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
                $message = 'Grade recorded successfully.';
            }
            
            return redirect()->back()->with('success', $message);
        }
        
        return redirect()->back();
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
}
