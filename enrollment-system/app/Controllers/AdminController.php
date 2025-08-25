<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\SchoolYearModel;
use App\Models\AdmissionTimeframeModel;
use App\Models\StudentModel;
use App\Models\StrandModel;
use App\Models\CurriculumModel;
use App\Models\TrackModel;

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
        $trackModel = new TrackModel();

        try {
            $data['strands'] = $strandModel->findAll();
            $data['tracks'] = $trackModel->getAllActiveTracks();
        } catch (\Exception $e) {
            $data['strands'] = [];
            $data['tracks'] = [];
            $data['error'] = 'Error loading strands: ' . $e->getMessage();
        }
        
        return view('admin/manage_strands', $data);
    }
    
    // Track management methods (integrated with strand management)
    public function addTrackFromStrands()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/strands');
        }

        $trackModel = new TrackModel();
        
        // Get form data
        $name = $this->request->getPost('track_name');
        $level = $this->request->getPost('track_level');
        $description = $this->request->getPost('track_description');
        $isActive = $this->request->getPost('track_is_active') ? 1 : 0;
        
        // Validation
        if (empty($name) || empty($level)) {
            return redirect()->to('/admin/strands')->with('error', 'Track name and level are required.');
        }
        
        // Prepare data
        $data = [
            'name' => $name,
            'level' => $level,
            'description' => $description,
            'is_active' => $isActive
        ];
        
        // Insert into database
        try {
            $result = $trackModel->insert($data);
            if ($result) {
                return redirect()->to('/admin/strands')->with('success', 'Track created successfully!');
            } else {
                return redirect()->to('/admin/strands')->with('error', 'Failed to create track.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/strands')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function editTrackFromStrands($id = null)
    {
        if ($this->request->getMethod() !== 'POST' || !$id) {
            return redirect()->to('/admin/strands');
        }
        
        $trackModel = new TrackModel();
        
        // Get form data
        $name = $this->request->getPost('track_name');
        $level = $this->request->getPost('track_level');
        $description = $this->request->getPost('track_description');
        $isActive = $this->request->getPost('track_is_active') ? 1 : 0;
        
        // Validation
        if (empty($name) || empty($level)) {
            return redirect()->to('/admin/strands')->with('error', 'Track name and level are required.');
        }
        
        // Prepare data
        $data = [
            'name' => $name,
            'level' => $level,
            'description' => $description,
            'is_active' => $isActive
        ];
        
        // Update database
        try {
            $result = $trackModel->update($id, $data);
            if ($result) {
                return redirect()->to('/admin/strands')->with('success', 'Track updated successfully!');
            } else {
                return redirect()->to('/admin/strands')->with('error', 'Failed to update track.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/strands')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function deleteTrackFromStrands($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/strands');
        }
        
        $trackModel = new TrackModel();
        $strandModel = new StrandModel();
        
        // Check if there are strands under this track
        $strandsUnderTrack = $strandModel->where('track_id', $id)->findAll();
        
        if (!empty($strandsUnderTrack)) {
            return redirect()->to('/admin/strands')->with('error', 'Cannot delete track. There are strands assigned to this track. Please delete or reassign the strands first.');
        }
        
        try {
            $result = $trackModel->delete($id);
            if ($result) {
                return redirect()->to('/admin/strands')->with('success', 'Track deleted successfully!');
            } else {
                return redirect()->to('/admin/strands')->with('error', 'Failed to delete track.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/strands')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function addStrand()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/strands');
        }

        $strandModel = new StrandModel();
        
        // Get form data
        $trackId = $this->request->getPost('track_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Validation
        if (empty($trackId) || empty($name)) {
            return redirect()->to('/admin/strands')->with('error', 'Track and strand name are required.');
        }
        
        // Prepare data
        $data = [
            'track_id' => $trackId,
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
        $trackId = $this->request->getPost('track_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Validation
        if (empty($trackId) || empty($name)) {
            return redirect()->to('/admin/strands')->with('error', 'Track and strand name are required.');
        }
        
        // Prepare data
        $data = [
            'track_id' => $trackId,
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
    
    // Curriculum Management Methods
    public function manageCurriculums()
    {
        $curriculumModel = new CurriculumModel();
        
        try {
            $data['curriculums'] = $curriculumModel->getAllActiveCurriculums();
        } catch (\Exception $e) {
            $data['curriculums'] = [];
            $data['error'] = 'Error loading curriculums: ' . $e->getMessage();
        }
        
        return view('admin/manage_curriculums', $data);
    }
    
    public function addCurriculum()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/curriculums');
        }

        $curriculumModel = new CurriculumModel();
        
        // Get form data
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Validation
        if (empty($name)) {
            return redirect()->to('/admin/curriculums')->with('error', 'Curriculum name is required.');
        }
        
        // Prepare data
        $data = [
            'name' => $name,
            'level' => 'jhs', // Always JHS
            'track' => null, // No track for curriculum
            'description' => $description,
            'is_active' => $isActive
        ];
        
        // Insert into database
        try {
            $result = $curriculumModel->insert($data);
            if ($result) {
                return redirect()->to('/admin/curriculums')->with('success', 'Curriculum created successfully!');
            } else {
                return redirect()->to('/admin/curriculums')->with('error', 'Failed to create curriculum.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/curriculums')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function editCurriculum($id = null)
    {
        if ($this->request->getMethod() !== 'POST' || !$id) {
            return redirect()->to('/admin/curriculums');
        }
        
        $curriculumModel = new CurriculumModel();
        
        // Get form data
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Validation
        if (empty($name)) {
            return redirect()->to('/admin/curriculums')->with('error', 'Curriculum name is required.');
        }
        
        // Prepare data
        $data = [
            'name' => $name,
            'level' => 'jhs', // Always JHS
            'track' => null, // No track for curriculum
            'description' => $description,
            'is_active' => $isActive
        ];
        
        // Update database
        try {
            $result = $curriculumModel->update($id, $data);
            if ($result) {
                return redirect()->to('/admin/curriculums')->with('success', 'Curriculum updated successfully!');
            } else {
                return redirect()->to('/admin/curriculums')->with('error', 'Failed to update curriculum.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/curriculums')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function deleteCurriculum($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/curriculums');
        }
        
        $curriculumModel = new CurriculumModel();
        
        try {
            $result = $curriculumModel->delete($id);
            if ($result) {
                return redirect()->to('/admin/curriculums')->with('success', 'Curriculum deleted successfully!');
            } else {
                return redirect()->to('/admin/curriculums')->with('error', 'Failed to delete curriculum.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/curriculums')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
