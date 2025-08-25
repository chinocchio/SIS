<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\StudentModel;
use App\Models\AdmissionTimeframeModel;
use App\Models\StrandModel;
use App\Models\CurriculumModel;

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
        $curriculumModel = new CurriculumModel();
        
        // Temporarily disable timeframe check for testing
        // Comment out this block to make admission always open
        
        if (!$admissionTimeframeModel->isAdmissionOpen()) {
            return view('admission/closed');
        }
        
        
        $data = [
            'strands' => $strandModel->getActiveStrandsWithTracks(),
            'curriculums' => $curriculumModel->getAllActiveCurriculums()
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
        $curriculumId = $this->request->getPost('curriculum_id');
        
        // Validate curriculum selection for JHS students
        if ($gradeLevel >= 7 && $gradeLevel <= 10 && empty($curriculumId)) {
            return redirect()->back()->with('error', 'Curriculum selection is required for Junior High School students.');
        }
        
        // Validate strand selection for SHS students
        if ($gradeLevel >= 11 && empty($strandId)) {
            return redirect()->back()->with('error', 'Strand selection is required for Senior High School students.');
        }
        
        // Determine admission type based on business rules
        $admissionType = $studentModel->determineAdmissionType($gradeLevel, $previousGradeLevel);
        
        // For SHS students, no documents needed (simplified process)
        $requiresDocuments = ($gradeLevel < 11);
        
        $data = [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'grade_level'  => $gradeLevel,
            'previous_grade_level' => ($gradeLevel < 11) ? ($previousGradeLevel ?: null) : null,
            'admission_type'=> $admissionType,
            'strand_id'    => ($gradeLevel >= 11) ? $strandId : null,
            'curriculum_id'=> ($gradeLevel >= 7 && $gradeLevel <= 10) ? $curriculumId : null,
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
