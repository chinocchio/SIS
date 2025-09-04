<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\UserModel;
use App\Models\DocumentModel;

class RegistrarController extends BaseController
{
    public function __construct()
    {
        // Check if user is logged in and is a registrar
        if (!session()->get('is_logged_in') || session()->get('role') !== 'registrar') {
            return redirect()->to('/auth/login');
        }
    }
    
    public function index()
    {
        return $this->manageStudents();
    }
    
    public function dashboard()
    {
        $studentModel = new StudentModel();
        
        $data = [
            'pendingEnrollments' => $studentModel->where('status', 'pending')->findAll(),
            'approvedEnrollments' => $studentModel->where('status', 'approved')->findAll(),
            'rejectedEnrollments' => $studentModel->where('status', 'rejected')->findAll(),
            'totalStudents' => $studentModel->countAllResults(),
            'pendingCount' => $studentModel->where('status', 'pending')->countAllResults(),
            'approvedCount' => $studentModel->where('status', 'approved')->countAllResults(),
            'rejectedCount' => $studentModel->where('status', 'rejected')->countAllResults()
        ];
        
        return view('registrar/dashboard', $data);
    }
    
    public function viewEnrollments($status = 'pending')
    {
        $studentModel = new StudentModel();
        
        $data = [
            'enrollments' => $studentModel->where('status', $status)->findAll(),
            'status' => $status
        ];
        
        return view('registrar/enrollments', $data);
    }
    
    public function viewStudent($studentId)
    {
        $studentModel = new StudentModel();
        $student = $studentModel->getStudentWithDetails($studentId);
        
        if (!$student) {
            return redirect()->to('/registrar/dashboard')->with('error', 'Student not found.');
        }
        
        $documentModel = new DocumentModel();
        $documents = $documentModel->getDocumentsByStudent($studentId);
        
        $data = [
            'student' => $student,
            'documents' => $documents
        ];
        
        return view('registrar/view_student', $data);
    }

         public function manageStudents()
     {
         $studentModel = new StudentModel();
         $search = $this->request->getGet('search');
         $status_filter = $this->request->getGet('status');
         $grade_filter = $this->request->getGet('grade_level');
         $enrollment_filter = $this->request->getGet('enrollment_type');
         $admission_filter = $this->request->getGet('admission_type');
         $export = $this->request->getGet('export');
         
         // Build query
         $query = $studentModel;
         
         // Apply search filter
         if (!empty($search)) {
             $query = $query->groupStart()
                           ->like('full_name', $search)
                           ->orLike('lrn', $search)
                           ->orLike('email', $search)
                           ->groupEnd();
         }
         
         // Apply status filter
         if (!empty($status_filter)) {
             $query = $query->where('status', $status_filter);
         }
         
         // Apply grade level filter
         if (!empty($grade_filter)) {
             $query = $query->where('grade_level', $grade_filter);
         }
         
         // Apply enrollment type filter
         if (!empty($enrollment_filter)) {
             $query = $query->where('enrollment_type', $enrollment_filter);
         }
         
         // Apply admission type filter
         if (!empty($admission_filter)) {
             $query = $query->where('admission_type', $admission_filter);
         }
         
         // Handle export
         if ($export) {
             return $this->exportStudents($query);
         }
         
         // Get paginated results
         $pager = $studentModel->pager;
         $students = $query->orderBy('created_at', 'DESC')
                           ->paginate(20);
         
         // Calculate summary statistics
         $totalStudents = $studentModel->countAllResults();
         $draftStudents = $studentModel->where('status', 'draft')->countAllResults();
         $pendingStudents = $studentModel->where('status', 'pending')->countAllResults();
         $approvedStudents = $studentModel->where('status', 'approved')->countAllResults();
         
         $data = [
             'students' => $students,
             'pager' => $pager,
             'search' => $search,
             'status_filter' => $status_filter,
             'grade_filter' => $grade_filter,
             'enrollment_filter' => $enrollment_filter,
             'admission_filter' => $admission_filter,
             'totalStudents' => $totalStudents,
             'draftStudents' => $draftStudents,
             'pendingStudents' => $pendingStudents,
             'approvedStudents' => $approvedStudents
         ];
         
         return view('registrar/manage_students', $data);
     }

    public function assignStudentToSection()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }

        $studentId = $this->request->getPost('student_id');
        $sectionId = $this->request->getPost('section_id');

        if (!$studentId || !$sectionId) {
            return redirect()->back()->with('error', 'Student ID and Section ID are required.');
        }

        $studentModel = new StudentModel();
        $sectionModel = new \App\Models\SectionModel();

        // Check if section exists and has capacity
        $section = $sectionModel->find($sectionId);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found.');
        }

        $capacity = $sectionModel->getSectionCapacity($sectionId);
        if ($capacity['current'] >= $capacity['max']) {
            return redirect()->back()->with('error', 'Section is at maximum capacity.');
        }

        // Get current student data
        $student = $studentModel->find($studentId);
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Update student's section assignment
        $updateData = [
            'section_id' => $sectionId
        ];

        // If student was previously assigned to a section, move it to previous_section_id
        if ($student['section_id']) {
            $updateData['previous_section_id'] = $student['section_id'];
        }

        if ($studentModel->update($studentId, $updateData)) {
            return redirect()->back()->with('success', 'Student assigned to section successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to assign student to section.');
        }
    }

    public function removeStudentFromSection($studentId)
    {
        if (!$studentId) {
            return redirect()->back()->with('error', 'Student ID required.');
        }

        $studentModel = new StudentModel();
        $student = $studentModel->find($studentId);

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        // Move current section to previous_section_id and clear current section
        $updateData = [
            'previous_section_id' => $student['section_id'],
            'section_id' => null
        ];

        if ($studentModel->update($studentId, $updateData)) {
            return redirect()->back()->with('success', 'Student removed from section successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to remove student from section.');
        }
    }

    // ==================== STUDENT CRUD METHODS ====================

    public function showAddStudentForm()
    {
        $strandModel = new \App\Models\StrandModel();
        $curriculumModel = new \App\Models\CurriculumModel();
        
        $data = [
            'strands' => $strandModel->findAll(),
            'curriculums' => $curriculumModel->findAll()
        ];
        
        return view('registrar/add_student', $data);
    }

    public function createStudent()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/registrar/students/add')->with('error', 'Invalid request method');
        }

        $studentModel = new StudentModel();
        
        // Get registrar's full name from session
        $registrarFirstName = session()->get('first_name');
        $registrarLastName = session()->get('last_name');
        $registrarFullName = trim($registrarFirstName . ' ' . $registrarLastName);
        
        // Get form data
        $data = [
            'lrn' => $this->request->getPost('lrn'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'full_name' => trim($this->request->getPost('first_name') . ' ' . $this->request->getPost('middle_name') . ' ' . $this->request->getPost('last_name')),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'birth_date' => $this->request->getPost('birth_date'),
            'gender' => $this->request->getPost('gender'),
            'grade_level' => $this->request->getPost('grade_level'),
            'previous_grade_level' => $this->request->getPost('previous_grade_level'),
            'admission_type' => $this->request->getPost('admission_type'),
            'enrollment_type' => $this->request->getPost('enrollment_type'),
            'strand_id' => $this->request->getPost('strand_id') ?: null,
            'curriculum_id' => $this->request->getPost('curriculum_id') ?: null,
            'previous_school' => $this->request->getPost('previous_school'),
            'status' => 'approved', // Registrar can directly approve
            'approved_by' => $registrarFullName,
            'approved_at' => date('Y-m-d H:i:s')
        ];

        // Validate required fields
        if (empty($data['lrn']) || empty($data['first_name']) || empty($data['last_name'])) {
            return redirect()->to('/registrar/students/add')->with('error', 'LRN, First Name, and Last Name are required');
        }

        // Check if LRN already exists
        if ($studentModel->where('lrn', $data['lrn'])->first()) {
            return redirect()->to('/registrar/students/add')->with('error', 'LRN already exists in the system');
        }

        // Check if email already exists
        if ($studentModel->where('email', $data['email'])->first()) {
            return redirect()->to('/registrar/students/add')->with('error', 'Email already exists in the system');
        }

        try {
            if ($studentModel->insert($data)) {
                return redirect()->to('/registrar/students')->with('success', 'Student created and approved successfully!');
            } else {
                return redirect()->to('/registrar/students/add')->with('error', 'Failed to create student. Please check your input.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Student creation error: ' . $e->getMessage());
            return redirect()->to('/registrar/students/add')->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }

    public function showEditStudentForm($studentId)
    {
        if (!$studentId) {
            return redirect()->to('/registrar/students')->with('error', 'Student ID required');
        }

        $studentModel = new StudentModel();
        $strandModel = new \App\Models\StrandModel();
        $curriculumModel = new \App\Models\CurriculumModel();

        $student = $studentModel->getStudentWithDetails($studentId);
        if (!$student) {
            return redirect()->to('/registrar/students')->with('error', 'Student not found');
        }

        $data = [
            'student' => $student,
            'strands' => $strandModel->findAll(),
            'curriculums' => $curriculumModel->findAll()
        ];

        return view('registrar/edit_student', $data);
    }

    public function updateStudent($studentId)
    {
        if (!$studentId) {
            return redirect()->to('/registrar/students')->with('error', 'Student ID required');
        }

        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/registrar/students/edit/' . $studentId)->with('error', 'Invalid request method');
        }

        $studentModel = new StudentModel();
        $student = $studentModel->find($studentId);
        
        if (!$student) {
            return redirect()->to('/registrar/students')->with('error', 'Student not found');
        }

        // Get form data
        $data = [
            'lrn' => $this->request->getPost('lrn'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'middle_name' => $this->request->getPost('middle_name'),
            'full_name' => trim($this->request->getPost('first_name') . ' ' . $this->request->getPost('middle_name') . ' ' . $this->request->getPost('last_name')),
            'email' => $this->request->getPost('email'),
            'birth_date' => $this->request->getPost('birth_date'),
            'gender' => $this->request->getPost('gender'),
            'grade_level' => $this->request->getPost('grade_level'),
            'previous_grade_level' => $this->request->getPost('previous_grade_level'),
            'admission_type' => $this->request->getPost('admission_type'),
            'enrollment_type' => $this->request->getPost('enrollment_type'),
            'strand_id' => $this->request->getPost('strand_id') ?: null,
            'curriculum_id' => $this->request->getPost('curriculum_id') ?: null,
            'previous_school' => $this->request->getPost('previous_school'),
            'previous_school_year' => $this->request->getPost('previous_school_year')
        ];

        // Update password only if provided
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Validate required fields
        if (empty($data['lrn']) || empty($data['first_name']) || empty($data['last_name'])) {
            return redirect()->to('/registrar/students/edit/' . $studentId)->with('error', 'LRN, First Name, and Last Name are required');
        }

        // Check if LRN already exists (excluding current student)
        $existingLrn = $studentModel->where('lrn', $data['lrn'])->where('id !=', $studentId)->first();
        if ($existingLrn) {
            return redirect()->to('/registrar/students/edit/' . $studentId)->with('error', 'LRN already exists in the system');
        }

        // Check if email already exists (excluding current student)
        $existingEmail = $studentModel->where('email', $data['email'])->where('id !=', $studentId)->first();
        if ($existingEmail) {
            return redirect()->to('/registrar/students/edit/' . $studentId)->with('error', 'Email already exists in the system');
        }

        try {
            if ($studentModel->update($studentId, $data)) {
                return redirect()->to('/registrar/students')->with('success', 'Student updated successfully!');
            } else {
                return redirect()->to('/registrar/students/edit/' . $studentId)->with('error', 'Failed to update student. Please check your input.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Student update error: ' . $e->getMessage());
            return redirect()->to('/registrar/students/edit/' . $studentId)->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    public function deleteStudent($studentId)
    {
        if (!$studentId) {
            return redirect()->to('/registrar/students')->with('error', 'Student ID required');
        }

        $studentModel = new StudentModel();
        $student = $studentModel->find($studentId);
        
        if (!$student) {
            return redirect()->to('/registrar/students')->with('error', 'Student not found');
        }

        try {
            if ($studentModel->delete($studentId)) {
                return redirect()->to('/registrar/students')->with('success', 'Student deleted successfully!');
            } else {
                return redirect()->to('/registrar/students')->with('error', 'Failed to delete student.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Student deletion error: ' . $e->getMessage());
            return redirect()->to('/registrar/students')->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }

    public function approveStudent($studentId)
    {
        if (!$studentId) {
            return redirect()->to('/registrar/students')->with('error', 'Student ID required');
        }

        $studentModel = new StudentModel();
        $student = $studentModel->find($studentId);
        
        if (!$student) {
            return redirect()->to('/registrar/students')->with('error', 'Student not found');
        }

        if ($student['status'] === 'approved') {
            return redirect()->to('/registrar/students')->with('error', 'Student is already approved');
        }

        // Get registrar's full name from session
        $registrarFirstName = session()->get('first_name');
        $registrarLastName = session()->get('last_name');
        $registrarFullName = trim($registrarFirstName . ' ' . $registrarLastName);

        $result = $studentModel->update($studentId, [
            'status' => 'approved',
            'approved_by' => $registrarFullName,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return redirect()->to('/registrar/students')->with('success', 'Student approved successfully!');
        } else {
            return redirect()->to('/registrar/students')->with('error', 'Failed to approve student.');
        }
    }

    public function rejectStudent($studentId)
    {
        if (!$studentId) {
            return redirect()->to('/registrar/students')->with('error', 'Student ID required');
        }

        $reason = $this->request->getPost('rejection_reason');
        
        if (empty($reason)) {
            return redirect()->to('/registrar/students')->with('error', 'Rejection reason is required.');
        }

        $studentModel = new StudentModel();
        $student = $studentModel->find($studentId);
        
        if (!$student) {
            return redirect()->to('/registrar/students')->with('error', 'Student not found');
        }

        // Get registrar's full name from session
        $registrarFirstName = session()->get('first_name');
        $registrarLastName = session()->get('last_name');
        $registrarFullName = trim($registrarFirstName . ' ' . $registrarLastName);

        $result = $studentModel->update($studentId, [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_by' => $registrarFullName,
            'rejected_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return redirect()->to('/registrar/students')->with('success', 'Student rejected successfully!');
        } else {
            return redirect()->to('/registrar/students')->with('error', 'Failed to reject student.');
        }
    }

    public function approveDocument($documentId)
    {
        $documentModel = new DocumentModel();
        $documentModel->approveDocument((int)$documentId);
        return redirect()->back()->with('success', 'Document approved.');
    }

    public function rejectDocument($documentId)
    {
        $documentModel = new DocumentModel();
        $documentModel->rejectDocument((int)$documentId);
        return redirect()->back()->with('success', 'Document rejected.');
    }

    public function viewDocument($documentId)
    {
        $documentModel = new DocumentModel();
        $doc = $documentModel->find((int) $documentId);
        if (!$doc) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        $fullPath = ROOTPATH . $doc['file_path'];
        if (!is_file($fullPath)) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        $mimeType = function_exists('mime_content_type') ? mime_content_type($fullPath) : 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($fullPath) . '"')
            ->setBody(file_get_contents($fullPath));
    }
    
    public function approveEnrollment($studentId)
    {
        $studentModel = new StudentModel();
        
        // Get registrar's full name from session
        $registrarFirstName = session()->get('first_name');
        $registrarLastName = session()->get('last_name');
        $registrarFullName = trim($registrarFirstName . ' ' . $registrarLastName);
        
        $result = $studentModel->update($studentId, [
            'status' => 'approved',
            'approved_by' => $registrarFullName,
            'approved_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return redirect()->back()->with('success', 'Enrollment approved successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to approve enrollment.');
        }
    }
    
    public function rejectEnrollment($studentId)
    {
        $reason = $this->request->getPost('rejection_reason');
        
        if (empty($reason)) {
            return redirect()->back()->with('error', 'Rejection reason is required.');
        }
        
        $studentModel = new StudentModel();
        
        // Get registrar's full name from session
        $registrarFirstName = session()->get('first_name');
        $registrarLastName = session()->get('last_name');
        $registrarFullName = trim($registrarFirstName . ' ' . $registrarLastName);
        
        $result = $studentModel->update($studentId, [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_by' => $registrarFullName,
            'rejected_at' => date('Y-m-d H:i:s')
        ]);
        
        if ($result) {
            return redirect()->back()->with('success', 'Enrollment rejected successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to reject enrollment.');
        }
    }
    
    public function searchStudents()
    {
        $search = $this->request->getGet('search');
        
        $studentModel = new StudentModel();
        
        if (!empty($search)) {
            $students = $studentModel->like('first_name', $search)
                                   ->orLike('last_name', $search)
                                   ->orLike('email', $search)
                                   ->findAll();
        } else {
            $students = $studentModel->findAll();
        }
        
        $data = [
            'students' => $students,
            'search' => $search
        ];
        
        return view('registrar/search_students', $data);
    }
    
         public function generateReport()
     {
         $studentModel = new StudentModel();
         
         $data = [
             'totalEnrollments' => $studentModel->countAllResults(),
             'pendingEnrollments' => $studentModel->where('status', 'pending')->countAllResults(),
             'approvedEnrollments' => $studentModel->where('status', 'approved')->countAllResults(),
             'rejectedEnrollments' => $studentModel->where('status', 'rejected')->countAllResults(),
             'enrollmentsByGrade' => $studentModel->select('grade_level, COUNT(*) as count')
                                                ->groupBy('grade_level')
                                                ->findAll(),
             'enrollmentsByType' => $studentModel->select('admission_type, COUNT(*) as count')
                                               ->groupBy('admission_type')
                                               ->findAll()
         ];
         
         return view('registrar/report', $data);
     }
     
     private function exportStudents($query)
     {
         $students = $query->findAll();
         
         // Set headers for CSV download
         header('Content-Type: text/csv');
         header('Content-Disposition: attachment; filename="students_export_' . date('Y-m-d_H-i-s') . '.csv"');
         
         // Create output stream
         $output = fopen('php://output', 'w');
         
         // Add CSV headers
         fputcsv($output, [
             'LRN',
             'Full Name',
             'Email',
             'Grade Level',
             'Enrollment Type',
             'Admission Type',
             'Status',
             'Previous School',
             'Strand',
             'Curriculum',
             'Section',
             'Approved By',
             'Rejected By',
             'Created Date'
         ]);
         
         // Add data rows
         foreach ($students as $student) {
             fputcsv($output, [
                 $student['lrn'],
                 $student['full_name'],
                 $student['email'],
                 'Grade ' . $student['grade_level'],
                 ucfirst($student['enrollment_type']),
                 ucfirst($student['admission_type']),
                 ucfirst($student['status']),
                 $student['previous_school'] ?? 'N/A',
                 $student['strand_name'] ?? 'N/A',
                 $student['curriculum_name'] ?? 'N/A',
                 $student['section_name'] ?? 'N/A',
                 $student['approved_by'] ?? 'N/A',
                 $student['rejected_by'] ?? 'N/A',
                 date('M d, Y', strtotime($student['created_at']))
             ]);
         }
         
         fclose($output);
         exit;
     }
}
