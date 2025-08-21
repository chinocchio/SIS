<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

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
            'schoolYears' => $schoolYearModel->findAll(),
            'admissionTimeframes' => $admissionTimeframeModel->getAllTimeframesWithSchoolYear()
        ];
        
        return view('admin/dashboard', $data);
    }
    
    public function createSchoolYear()
    {
        if ($this->request->getMethod() === 'POST') {
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
    
    public function deactivateSchoolYear($id)
    {
        $schoolYearModel = new SchoolYearModel();
        $schoolYearModel->deactivateSchoolYear($id);
        
        return redirect()->to('/admin')->with('success', 'School year deactivated successfully.');
    }
    
    public function deleteSchoolYear($id)
    {
        $schoolYearModel = new SchoolYearModel();
        
        try {
            $result = $schoolYearModel->delete($id);
            if ($result) {
                return redirect()->to('/admin')->with('success', 'School year deleted successfully.');
            } else {
                return redirect()->to('/admin')->with('error', 'Failed to delete school year.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function createAdmissionTimeframe()
    {
        if ($this->request->getMethod() === 'POST') {
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
    
    public function editAdmissionTimeframe($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin')->with('error', 'Invalid timeframe ID.');
        }
        
        $admissionTimeframeModel = new AdmissionTimeframeModel();
        $schoolYearModel = new SchoolYearModel();
        
        $timeframe = $admissionTimeframeModel->find($id);
        if (!$timeframe) {
            return redirect()->to('/admin')->with('error', 'Timeframe not found.');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $data = [
                'school_year_id' => $this->request->getPost('school_year_id'),
                'start_date' => $this->request->getPost('start_date'),
                'end_date' => $this->request->getPost('end_date')
            ];
            
            try {
                $result = $admissionTimeframeModel->update($id, $data);
                if ($result) {
                    return redirect()->to('/admin')->with('success', 'Admission timeframe updated successfully.');
                } else {
                    return redirect()->to('/admin')->with('error', 'Failed to update admission timeframe.');
                }
            } catch (\Exception $e) {
                return redirect()->to('/admin')->with('error', 'Error: ' . $e->getMessage());
            }
        }
        
        $data = [
            'timeframe' => $timeframe,
            'schoolYears' => $schoolYearModel->findAll()
        ];
        
        return view('admin/edit_admission_timeframe', $data);
    }
    
    public function deleteAdmissionTimeframe($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin')->with('error', 'Invalid timeframe ID.');
        }
        
        $admissionTimeframeModel = new AdmissionTimeframeModel();
        
        try {
            $result = $admissionTimeframeModel->delete($id);
            if ($result) {
                return redirect()->to('/admin')->with('success', 'Admission timeframe deleted successfully.');
            } else {
                return redirect()->to('/admin')->with('error', 'Failed to delete admission timeframe.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin')->with('error', 'Error: ' . $e->getMessage());
        }
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

        try {
            $data['strands'] = $strandModel->findAll();
        } catch (\Exception $e) {
            $data['strands'] = [];
            $data['error'] = 'Error loading strands: ' . $e->getMessage();
        }
        
        return view('admin/manage_strands', $data);
    }
    
    public function addStrand()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/strands');
        }

        $strandModel = new StrandModel();
        
        // Get form data
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Validation
        if (empty($name)) {
            return redirect()->to('/admin/strands')->with('error', 'Strand name is required.');
        }
        
        // Prepare data
        $data = [
            'name' => $name,
            'description' => $description,
            'is_active' => $isActive
        ];
        
        // Insert into database
        try {
            $result = $strandModel->insert($data);
            if ($result) {
                return redirect()->to('/admin/strands')->with('success', 'Strand created successfully!');
            } else {
                return redirect()->to('/admin/strands')->with('error', 'Failed to create strand.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/strands')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function editStrand($id = null)
    {
        if ($this->request->getMethod() !== 'POST' || !$id) {
            return redirect()->to('/admin/strands');
        }
        
        $strandModel = new StrandModel();
        
        // Get form data
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Validation
        if (empty($name)) {
            return redirect()->to('/admin/strands')->with('error', 'Strand name is required.');
        }
        
        // Prepare data
        $data = [
            'name' => $name,
            'description' => $description,
            'is_active' => $isActive
        ];
        
        // Update database
        try {
            $result = $strandModel->update($id, $data);
            if ($result) {
                return redirect()->to('/admin/strands')->with('success', 'Strand updated successfully!');
            } else {
                return redirect()->to('/admin/strands')->with('error', 'Failed to update strand.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/strands')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function deleteStrand($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/strands');
        }
        
        $strandModel = new StrandModel();
        
        try {
            $result = $strandModel->delete($id);
            if ($result) {
                return redirect()->to('/admin/strands')->with('success', 'Strand deleted successfully!');
            } else {
                return redirect()->to('/admin/strands')->with('error', 'Failed to delete strand.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/strands')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    

}
