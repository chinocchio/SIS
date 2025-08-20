<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\StudentModel;
use App\Models\AdmissionTimeframeModel;
use App\Models\StrandModel;

class AdmissionController extends BaseController
{
    public function index()
    {
        return view('landing');
    }
    
    public function showForm()
    {
        // Check if admission is open
        $admissionTimeframeModel = new AdmissionTimeframeModel();
        $strandModel = new StrandModel();
        
        // Temporarily disable timeframe check for testing
        // Comment out this block to make admission always open
        
        if (!$admissionTimeframeModel->isAdmissionOpen()) {
            return view('admission/closed');
        }
        
        
        $data = [
            'strands' => $strandModel->getActiveStrands()
        ];
        
        return view('admission/form', $data);
    }

    public function submit()
    {
        // Temporarily disable timeframe check for testing
        // Comment out this block to make admission always open
        /*
        $admissionTimeframeModel = new AdmissionTimeframeModel();
        if (!$admissionTimeframeModel->isAdmissionOpen()) {
            return redirect()->to('/admission')->with('error', 'Admission period is closed.');
        }
        */
        
        $studentModel = new StudentModel();
        
        $gradeLevel = $this->request->getPost('grade_level');
        $previousGradeLevel = $this->request->getPost('previous_grade_level');
        $strandId = $this->request->getPost('strand_id');
        
        // Determine admission type based on business rules
        $admissionType = $studentModel->determineAdmissionType($gradeLevel, $previousGradeLevel);
        
        // Validate strand selection for SHS
        if ($gradeLevel >= 11 && empty($strandId)) {
            return redirect()->back()->with('error', 'Strand selection is required for Senior High School.');
        }
        
        // For JHS graduates going to SHS, no documents needed
        $requiresDocuments = true;
        if ($gradeLevel == 11 && $previousGradeLevel == 10) {
            $requiresDocuments = false;
        }
        
        $data = [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'grade_level'  => $gradeLevel,
            'previous_grade_level' => $previousGradeLevel ?: null,
            'admission_type'=> $admissionType,
            'strand_id'    => $strandId ?: null,
            'status'       => 'pending'
        ];

        // Debug: Log the data being inserted
        log_message('info', 'Admission data: ' . json_encode($data));

        try {
            $result = $studentModel->insert($data);
            log_message('info', 'Admission insert result: ' . $result);
        } catch (\Exception $e) {
            log_message('error', 'Admission insert error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error submitting application: ' . $e->getMessage());
        }

        $message = 'Your admission has been submitted. Please login.';
        if (!$requiresDocuments) {
            $message .= ' No documents required for JHS to SHS transition.';
        }

        return redirect()->to('/student/login')->with('success', $message);
    }
}
