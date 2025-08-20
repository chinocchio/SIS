<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SchoolYearModel;
use App\Models\AdmissionTimeframeModel;
use App\Models\StudentModel;
use App\Models\StrandModel;

class AdminController extends BaseController
{
    public function index()
    {
        $schoolYearModel = new SchoolYearModel();
        $admissionTimeframeModel = new AdmissionTimeframeModel();
        
        $data = [
            'activeSchoolYear' => $schoolYearModel->getActiveSchoolYear(),
            'admissionTimeframe' => $admissionTimeframeModel->getCurrentTimeframe(),
            'schoolYears' => $schoolYearModel->findAll()
        ];
        
        return view('admin/dashboard', $data);
    }
    
    public function createSchoolYear()
    {
        if ($this->request->getMethod() === 'post') {
            $schoolYearModel = new SchoolYearModel();
            
            $data = [
                'name' => $this->request->getPost('name'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date')
            ];
            
            $schoolYearModel->createNewSchoolYear($data);
            
            return redirect()->to('/admin')->with('success', 'New school year created successfully.');
        }
        
        return view('admin/create_school_year');
    }
    
    public function activateSchoolYear($id)
    {
        $schoolYearModel = new SchoolYearModel();
        $schoolYearModel->activateSchoolYear($id);
        
        return redirect()->to('/admin')->with('success', 'School year activated successfully.');
    }
    
    public function createAdmissionTimeframe()
    {
        if ($this->request->getMethod() === 'post') {
            $admissionTimeframeModel = new AdmissionTimeframeModel();
            
            $data = [
                'school_year_id' => $this->request->getPost('school_year_id'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date')
            ];
            
            $admissionTimeframeModel->insert($data);
            
            return redirect()->to('/admin')->with('success', 'Admission timeframe created successfully.');
        }
        
        $schoolYearModel = new SchoolYearModel();
        $data['schoolYears'] = $schoolYearModel->findAll();
        
        return view('admin/create_admission_timeframe', $data);
    }
    
    public function promoteStudents()
    {
        $studentModel = new StudentModel();
        $schoolYearModel = new SchoolYearModel();
        
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        if ($activeSchoolYear) {
            $studentModel->promoteStudentsToNextGrade($activeSchoolYear['id']);
            return redirect()->to('/admin')->with('success', 'Students promoted to next grade level successfully.');
        }
        
        return redirect()->to('/admin')->with('error', 'No active school year found.');
    }
    
    public function manageStrands()
    {
        $strandModel = new StrandModel();
        
        if ($this->request->getMethod() === 'post') {
            $data = [
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'is_active' => $this->request->getPost('is_active') ? 1 : 0
            ];
            
            if ($this->request->getPost('id')) {
                $strandModel->update($this->request->getPost('id'), $data);
                $message = 'Strand updated successfully.';
            } else {
                $strandModel->insert($data);
                $message = 'Strand created successfully.';
            }
            
            return redirect()->to('/admin/strands')->with('success', $message);
        }
        
        $data['strands'] = $strandModel->findAll();
        return view('admin/manage_strands', $data);
    }
}
