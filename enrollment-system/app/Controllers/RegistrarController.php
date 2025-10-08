<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\UserModel;
use App\Models\DocumentModel;

class RegistrarController extends BaseController
{
    // Auth for registrar is enforced via the 'registrarauth' route filter
    
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
        
        // Get registrar's ID from session
        $registrarId = session()->get('user_id');
        
        // Get form data - align with database schema
        $data = [
            'lrn' => $this->request->getPost('lrn'),
            'first_name' => '', // Empty since we're using full_name only
            'last_name' => '', // Empty since we're using full_name only
            'middle_name' => null, // Null since we're using full_name only
            'full_name' => trim($this->request->getPost('full_name')),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'birth_date' => $this->request->getPost('birth_date') ?: null,
            'gender' => $this->request->getPost('gender') ?: null,
            'grade_level' => $this->request->getPost('grade_level'),
            'previous_grade_level' => null, // Not collected in form anymore
            'admission_type' => $this->request->getPost('admission_type') ?: 'regular',
            'enrollment_type' => $this->request->getPost('enrollment_type') ?: 'new',
            'previous_school' => null, // Not collected in form anymore
            'strand_id' => $this->request->getPost('strand_id') ?: null,
            'curriculum_id' => $this->request->getPost('curriculum_id') ?: null,
            'section_id' => null, // Will be assigned later
            'previous_section_id' => null,
            'previous_school_year' => null,
            'status' => 'pending', // Student needs to be approved separately
            'approved_by' => null // Will be set when approved
        ];

        // Validate required fields
        if (empty($data['lrn']) || empty($data['full_name'])) {
            return redirect()->to('/registrar/students/add')->with('error', 'LRN and Full Name are required');
        }

        // Check if LRN already exists
        if ($studentModel->where('lrn', $data['lrn'])->first()) {
            return redirect()->to('/registrar/students/add')->with('error', 'LRN already exists in the system');
        }

        // Check if email already exists
        if ($studentModel->where('email', $data['email'])->first()) {
            return redirect()->to('/registrar/students/add')->with('error', 'Email already exists in the system');
        }

        // Log the data being inserted for debugging
        log_message('info', 'Attempting to create student with data: ' . json_encode($data, JSON_PRETTY_PRINT));
        
        // Clean up the data array - remove null values that might cause issues
        $cleanData = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });
        
        log_message('info', 'Cleaned data for insertion: ' . json_encode($cleanData, JSON_PRETTY_PRINT));

        try {
            // Check if insert was successful
            $result = $studentModel->insert($cleanData);
            
            if ($result) {
                log_message('info', 'Student created successfully with ID: ' . $result);
                return redirect()->to('/registrar/students')->with('success', 'Student created successfully! Status: Pending approval.');
            } else {
                // Get the last database error
                $db = \Config\Database::connect();
                $error = $db->error();
                log_message('error', 'Student creation failed - Database error: ' . json_encode($error));
                log_message('error', 'Student model errors: ' . json_encode($studentModel->errors()));
                return redirect()->to('/registrar/students/add')->with('error', 'Failed to create student. Database error: ' . ($error['message'] ?? 'Unknown error'));
            }
        } catch (\Exception $e) {
            log_message('error', 'Student creation exception: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
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

        $fullPath = $this->resolveDocumentFullPath($doc['file_path']);
        if (!$fullPath) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        $mimeType = function_exists('mime_content_type') ? mime_content_type($fullPath) : 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . basename($fullPath) . '"')
            ->setBody(file_get_contents($fullPath));
    }
    
    public function downloadDocument($documentId)
    {
        $documentModel = new DocumentModel();
        $doc = $documentModel->find((int) $documentId);
        if (!$doc) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        $fullPath = $this->resolveDocumentFullPath($doc['file_path']);
        if (!$fullPath) {
            return redirect()->back()->with('error', 'File not found on server.');
        }

        return $this->response->download($fullPath, null, true);
    }

    private function resolveDocumentFullPath(string $storedPath): ?string
    {
        // If already absolute and exists
        if (is_file($storedPath)) {
            return $storedPath;
        }
        $candidates = [];
        // Normalize leading slash
        $trimmed = ltrim($storedPath, '/\\');
        $candidates[] = ROOTPATH . $trimmed;
        if (defined('FCPATH')) {
            $candidates[] = rtrim(FCPATH, '/\\') . DIRECTORY_SEPARATOR . $trimmed;
        }
        if (defined('WRITEPATH')) {
            $candidates[] = rtrim(WRITEPATH, '/\\') . DIRECTORY_SEPARATOR . $trimmed;
        }
        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }
        return null;
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
    
    public function changePassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $userId = session()->get('user_id');
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_password');
            
            // Validate passwords
            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'New passwords do not match.');
            }
            
            if (strlen($newPassword) < 6) {
                return redirect()->back()->with('error', 'New password must be at least 6 characters long.');
            }
            
            // Get current user data
            $userModel = new UserModel();
            $user = $userModel->find($userId);
            
            if (!$user) {
                return redirect()->back()->with('error', 'User not found.');
            }
            
            // Verify current password
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
            
            // Update password
            if ($userModel->updatePassword($userId, $newPassword)) {
                return redirect()->back()->with('success', 'Password changed successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update password.');
            }
        }
        
        return view('registrar/change_password');
    }
}
