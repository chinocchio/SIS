<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\DocumentModel;
use App\Models\SchoolYearModel;

class StudentController extends BaseController
{
    public function login()
    {
        return view('student/login');
    }
    
    public function index()
    {
        $studentId = session()->get('user_id');
        $studentModel = new StudentModel();
        $documentModel = new DocumentModel();
        $schoolYearModel = new SchoolYearModel();
        
        // Get student details with all related information
        $student = $studentModel->getStudentWithDetails($studentId);
        
        if (!$student) {
            return redirect()->to('/auth/login')->with('error', 'Student not found.');
        }
        
        // Get student documents
        $documents = $documentModel->where('student_id', $studentId)
                                  ->orderBy('uploaded_at', 'DESC')
                                  ->findAll();
        
        // Get active school year
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        // Get recorded grades from student_grades table
        $grades = [];
        try {
            if ($activeSchoolYear) {
                $db = \Config\Database::connect();
                $gradesQuery = $db->table('student_grades sg')
                                ->select('sg.*, s.name as subject_name, s.code as subject_code, s.grade_level, s.quarter, s.is_core')
                                ->join('subjects s', 's.id = sg.subject_id')
                                ->where('sg.student_id', $studentId)
                                ->where('sg.school_year_id', $activeSchoolYear['id'])
                                ->orderBy('s.grade_level', 'ASC')
                                ->orderBy('s.quarter', 'ASC')
                                ->orderBy('s.name', 'ASC')
                                ->get();

                $grades = $gradesQuery->getResultArray();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error fetching grades for student ' . $studentId . ': ' . $e->getMessage());
        }
        
        $data = [
            'student' => $student,
            'documents' => $documents,
            'grades' => $grades,
            'activeSchoolYear' => $activeSchoolYear
        ];
        
        return view('student/dashboard', $data);
    }
    
    public function submitDocument()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        
        $studentId = session()->get('user_id');
        $documentType = $this->request->getPost('document_type');
        $description = $this->request->getPost('description');
        
        // Validate document type
        $allowedTypes = ['birth_certificate', 'report_card', 'good_moral', 'form_137', 'id_picture', 'other'];
        if (!in_array($documentType, $allowedTypes)) {
            return redirect()->back()->with('error', 'Invalid document type.');
        }
        
        // Handle file upload
        $file = $this->request->getFile('document_file');
        
        if (!$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid file.');
        }
        
        // Validate file size (max 5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            return redirect()->back()->with('error', 'File size must be less than 5MB.');
        }
        
        // Validate file type
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $extension = strtolower($file->getExtension());
        if (!in_array($extension, $allowedExtensions)) {
            return redirect()->back()->with('error', 'Only JPG, PNG, and PDF files are allowed.');
        }
        
        // Generate unique filename
        $newName = $file->getRandomName();
        $uploadPath = 'uploads/documents/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Move file to upload directory
        if ($file->move($uploadPath, $newName)) {
            // Save document record to database
            $documentModel = new DocumentModel();
            $documentData = [
                'student_id' => $studentId,
                'document_type' => $documentType,
                'file_path' => $uploadPath . $newName,
                'original_filename' => $file->getClientName(),
                'file_size' => $file->getSize(),
                'description' => $description,
                'status' => 'pending',
                'uploaded_at' => date('Y-m-d H:i:s')
            ];
            
            if ($documentModel->insert($documentData)) {
                return redirect()->back()->with('success', 'Document uploaded successfully! It will be reviewed by the registrar.');
            } else {
                // Delete uploaded file if database insert fails
                unlink($uploadPath . $newName);
                return redirect()->back()->with('error', 'Failed to save document record.');
            }
        } else {
            return redirect()->back()->with('error', 'Failed to upload file.');
        }
    }
    
    public function changePassword()
    {
        if ($this->request->getMethod() === 'POST') {
            $studentId = session()->get('user_id');
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
            
            // Get current student data
            $studentModel = new StudentModel();
            $student = $studentModel->find($studentId);
            
            if (!$student) {
                return redirect()->back()->with('error', 'Student not found.');
            }
            
            // Verify current password
            if (!password_verify($currentPassword, $student['password'])) {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
            
            // Update password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            if ($studentModel->update($studentId, ['password' => $hashedPassword])) {
                return redirect()->back()->with('success', 'Password changed successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update password.');
            }
        }
        
        return view('student/change_password');
    }
}