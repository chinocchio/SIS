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
        return $this->dashboard();
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
        $student = $studentModel->find($studentId);
        
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
        
        $result = $studentModel->update($studentId, [
            'status' => 'approved',
            'approved_by' => session()->get('user_id'),
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
        
        $result = $studentModel->update($studentId, [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_by' => session()->get('user_id'),
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
}
