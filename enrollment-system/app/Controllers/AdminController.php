<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\SchoolYearModel;
use App\Models\AdmissionTimeframeModel;
use App\Models\StudentModel;
use App\Models\StrandModel;
use App\Models\CurriculumModel;
use App\Models\SubjectModel;
use App\Models\TrackModel;
use App\Libraries\OcrService;

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
        $subjectModel = new SubjectModel();
        
        // Check if there are subjects under this curriculum
        $subjectsUnderCurriculum = $subjectModel->where('curriculum_id', $id)->findAll();
        
        if (!empty($subjectsUnderCurriculum)) {
            return redirect()->to('/admin/curriculums')->with('error', 'Cannot delete curriculum. There are subjects assigned to this curriculum. Please delete or reassign the subjects first.');
        }
        
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
    
    // ==================== SUBJECT MANAGEMENT METHODS ====================
    
    public function manageSubjects()
    {
        $subjectModel = new SubjectModel();
        $curriculumModel = new CurriculumModel();
        
        try {
            $data['subjects'] = $subjectModel->getAllActiveSubjectsWithCurriculum();
            $data['curriculums'] = $curriculumModel->getAllActiveCurriculums();
        } catch (\Exception $e) {
            $data['subjects'] = [];
            $data['curriculums'] = [];
            $data['error'] = 'Error loading subjects: ' . $e->getMessage();
        }
        
        return view('admin/manage_subjects', $data);
    }
    
    public function showAddSubjectForm()
    {
        $curriculumModel = new CurriculumModel();
        
        $data = [
            'curriculums' => $curriculumModel->getAllActiveCurriculums()
        ];
        
        return view('admin/add_subject', $data);
    }
    
    public function addSubject()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/subjects');
        }

        // Debug: Log the request
        log_message('info', 'addSubject called with POST data: ' . json_encode($this->request->getPost()));

        $subjectModel = new SubjectModel();
        
        // Get form data
        $curriculumId = $this->request->getPost('curriculum_id');
        $code = $this->request->getPost('code');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $units = $this->request->getPost('units');
        $isCore = $this->request->getPost('is_core') ? 1 : 0;
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Debug: Log the extracted data
        log_message('info', 'Extracted data - curriculum_id: ' . $curriculumId . ', code: ' . $code . ', name: ' . $name . ', units: ' . $units);
        
        // Validation
        if (empty($curriculumId) || empty($code) || empty($name) || empty($units)) {
            log_message('error', 'Validation failed - missing required fields');
            return redirect()->to('/admin/subjects')->with('error', 'Curriculum, code, name, and units are required.');
        }
        
        // Check if subject code is unique within the curriculum
        if (!$subjectModel->isCodeUniqueInCurriculum($code, $curriculumId)) {
            log_message('error', 'Subject code already exists in curriculum: ' . $code . ' in ' . $curriculumId);
            return redirect()->to('/admin/subjects')->with('error', 'Subject code already exists in this curriculum.');
        }
        
        // Prepare data
        $data = [
            'curriculum_id' => $curriculumId,
            'code' => strtoupper(trim($code)),
            'name' => trim($name),
            'description' => trim($description),
            'units' => $units,
            'is_core' => $isCore,
            'is_active' => $isActive
        ];
        
        // Debug: Log the prepared data
        log_message('info', 'Prepared data for insert: ' . json_encode($data));
        
        // Insert into database
        try {
            $result = $subjectModel->insert($data);
            if ($result) {
                log_message('info', 'Subject created successfully with ID: ' . $result);
                return redirect()->to('/admin/subjects')->with('success', 'Subject created successfully!');
            } else {
                log_message('error', 'Failed to create subject - insert returned false');
                return redirect()->to('/admin/subjects')->with('error', 'Failed to create subject.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception creating subject: ' . $e->getMessage());
            return redirect()->to('/admin/subjects')->with('error', 'Error: ' . $e->getMessage());
        }
    }
    
    public function editSubject($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/subjects')->with('error', 'Subject ID required');
        }
        
        $subjectModel = new SubjectModel();
        $curriculumModel = new CurriculumModel();
        $subject = $subjectModel->find($id);
        
        if (!$subject) {
            return redirect()->to('/admin/subjects')->with('error', 'Subject not found');
        }
        
        if ($this->request->getMethod() === 'POST') {
            // Handle update
            $curriculumId = $this->request->getPost('curriculum_id');
            $code = $this->request->getPost('code');
            $name = $this->request->getPost('name');
            $description = $this->request->getPost('description');
            $units = $this->request->getPost('units');
            $isCore = $this->request->getPost('is_core') ? 1 : 0;
            $isActive = $this->request->getPost('is_active') ? 1 : 0;
            
            // Check if subject code is unique within the curriculum (excluding current subject)
            if (!$subjectModel->isCodeUniqueInCurriculum($code, $curriculumId, $id)) {
                return redirect()->to('/admin/subjects/edit/' . $id)->with('error', 'Subject code already exists in this curriculum.');
            }
            
            $data = [
                'curriculum_id' => $curriculumId,
                'code' => strtoupper(trim($code)),
                'name' => trim($name),
                'description' => trim($description),
                'units' => $units,
                'is_core' => $isCore,
                'is_active' => $isActive
            ];
            
            try {
                $subjectModel->update($id, $data);
                return redirect()->to('/admin/subjects')->with('success', 'Subject updated successfully');
            } catch (\Exception $e) {
                return redirect()->to('/admin/subjects/edit/' . $id)->with('error', 'Error updating subject: ' . $e->getMessage());
            }
        }
        
        $data = [
            'subject' => $subject,
            'curriculums' => $curriculumModel->getAllActiveCurriculums()
        ];
        
        return view('admin/edit_subject', $data);
    }
    
    public function deleteSubject($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/subjects')->with('error', 'Subject ID required');
        }
        
        $subjectModel = new SubjectModel();
        
        try {
            $result = $subjectModel->delete($id);
            if ($result) {
                return redirect()->to('/admin/subjects')->with('success', 'Subject deleted successfully');
            } else {
                return redirect()->to('/admin/subjects')->with('error', 'Failed to delete subject');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/subjects')->with('error', 'Error deleting subject: ' . $e->getMessage());
        }
    }
    
    public function getSubjectsByCurriculum()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'error' => 'Invalid request method']);
        }
        
        $curriculumId = $this->request->getPost('curriculum_id');
        
        if (empty($curriculumId)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Curriculum ID is required']);
        }
        
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel->getSubjectsByCurriculum($curriculumId);
        
        return $this->response->setJSON([
            'success' => true,
            'subjects' => $subjects
        ]);
    }
    
    // ==================== STUDENT MANAGEMENT METHODS ====================
    
    public function manageStudents()
    {
        $studentModel = new StudentModel();
        $search = $this->request->getGet('search');
        
        if ($search) {
            $students = $studentModel->searchStudents($search);
        } else {
            $students = $studentModel->getAllStudentsWithPagination();
        }
        
        // Get student counts for summary
        $totalStudents = $studentModel->countAll();
        $draftStudents = $studentModel->where('status', 'draft')->countAllResults();
        $pendingStudents = $studentModel->where('status', 'pending')->countAllResults();
        $approvedStudents = $studentModel->where('status', 'approved')->countAllResults();
        
        $data = [
            'students' => $students['students'] ?? $students,
            'pager' => $students['pager'] ?? null,
            'totalStudents' => $totalStudents,
            'draftStudents' => $draftStudents,
            'pendingStudents' => $pendingStudents,
            'approvedStudents' => $approvedStudents
        ];
        
        return view('admin/manage_students', $data);
    }
    
    public function showAddStudentForm()
    {
        $strandModel = new StrandModel();
        $curriculumModel = new CurriculumModel();
        
        $data = [
            'strands' => $strandModel->getActiveStrandsWithTracks(),
            'curriculums' => $curriculumModel->getAllActiveCurriculums()
        ];
        
        return view('admin/add_student_sf9', $data);
    }
    
    public function addStudentViaSF9()
    {
        // This method handles the SF9 upload and OCR processing
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['success' => false, 'error' => 'Invalid request method']);
        }
        
        $file = $this->request->getFile('sf9_file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'error' => 'Please select a valid SF9 file']);
        }
        
        // Upload file
        $newName = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads/sf9_documents';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $file->move($uploadPath, $newName);
        
        // Process OCR
        $ocrService = new OcrService();
        $ocrResult = $ocrService->extractText($uploadPath . DIRECTORY_SEPARATOR . $newName);
        
        if (!$ocrResult['success']) {
            return $this->response->setJSON(['success' => false, 'error' => 'OCR processing failed: ' . $ocrResult['error']]);
        }
        
        // Extract SF9 specific information
        $extractedData = $this->extractSF9Information($ocrResult['text']);
        
        // DEBUG: Log the data for debugging
        log_message('info', 'SF9 OCR Debug - Raw Text: ' . substr($ocrResult['text'], 0, 500));
        log_message('info', 'SF9 OCR Debug - Extracted Data: ' . json_encode($extractedData));
        log_message('info', 'SF9 OCR Debug - File Path: ' . $uploadPath . DIRECTORY_SEPARATOR . $newName);
        log_message('info', 'SF9 OCR Debug - Full Name Found: ' . ($extractedData['full_name'] ?? 'NOT FOUND'));
        log_message('info', 'SF9 OCR Debug - Grade Level Processing: SF9 Grade=' . ($extractedData['previous_grade_level'] ?? 'NOT FOUND') . ', Enrolling Grade=' . ($extractedData['grade_level'] ?? 'NOT FOUND'));
        
        return $this->response->setJSON([
            'success' => true,
            'extracted_data' => $extractedData,
            'message' => 'SF9 processed successfully',
            'debug_info' => [
                'text_length' => strlen($ocrResult['text']),
                'extracted_fields' => array_keys($extractedData),
                'full_name_found' => isset($extractedData['full_name']) ? $extractedData['full_name'] : 'NOT FOUND',
                'grade_processing' => [
                    'sf9_grade' => $extractedData['previous_grade_level'] ?? 'NOT FOUND',
                    'enrolling_grade' => $extractedData['grade_level'] ?? 'NOT FOUND'
                ],
                'file_path' => $uploadPath . DIRECTORY_SEPARATOR . $newName
            ]
        ]);
    }
    
    private function extractSF9Information($text)
    {
        $text = strtolower($text);
        $extracted = [];
        
        // Extract LRN (12-digit number)
        if (preg_match('/\b(\d{12})\b/', $text, $matches)) {
            $extracted['lrn'] = $matches[1];
        }
        
        // Extract full name (look for common patterns)
        if (preg_match('/\b(?:student name|name of student|complete name|name):\s*([a-z\s,]+)/i', $text, $matches)) {
            $fullName = trim($matches[1]);
            if (!empty($fullName)) {
                // Keep the exact format from the document (e.g., "Lastname, Firstname MiddleInitial")
                $extracted['full_name'] = ucwords($fullName);
            }
        }
        
        // Extract birth date
        if (preg_match('/\b(?:date of birth|birth date|born):\s*(\d{1,2}\/\d{1,2}\/\d{4})/i', $text, $matches)) {
            $extracted['birth_date'] = date('Y-m-d', strtotime($matches[1]));
        }
        
        // Extract gender
        if (preg_match('/\b(?:sex|gender):\s*(male|female)/i', $text, $matches)) {
            $extracted['gender'] = ucfirst($matches[1]);
        }
        
        // Extract grade level (handle both numeric and spelled-out numbers)
        $gradeLevel = null;
        
        // Try numeric grade first
        if (preg_match('/\b(?:grade|level):\s*(\d+)/i', $text, $matches)) {
            $gradeLevel = (int)$matches[1];
        }
        
        // If no numeric grade found, try spelled-out numbers
        if ($gradeLevel === null) {
            $spelledNumbers = [
                'zero' => 0, 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4,
                'five' => 5, 'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 9,
                'ten' => 10, 'eleven' => 11, 'twelve' => 12, 'thirteen' => 13,
                'fourteen' => 14, 'fifteen' => 15, 'sixteen' => 16, 'seventeen' => 17,
                'eighteen' => 18, 'nineteen' => 19, 'twenty' => 20
            ];
            
            foreach ($spelledNumbers as $word => $number) {
                if (preg_match('/\b(?:grade|level)\s+' . $word . '\b/i', $text)) {
                    $gradeLevel = $number;
                    break;
                }
            }
        }
        
        // If grade level found, increment by 1 (SF9 shows current grade, student enrolls in next grade)
        if ($gradeLevel !== null) {
            $extracted['grade_level'] = $gradeLevel + 1;
            $extracted['previous_grade_level'] = $gradeLevel; // Store the grade from SF9
        }
        
        // Extract previous school
        if (preg_match('/\b(?:school|institution):\s*([a-z\s,]+)/i', $text, $matches)) {
            $extracted['previous_school'] = ucwords(trim($matches[1]));
        }
        
        return $extracted;
    }
    public function createStudent()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/students/add')->with('error', 'Invalid request method');
        }
        
        $studentModel = new StudentModel();
        
        // Validate required fields - only LRN and full name are mandatory
        $lrn = $this->request->getPost('lrn');
        $full_name = $this->request->getPost('full_name');
        
        if (empty($lrn) || empty($full_name)) {
            return redirect()->to('/admin/students/add')->with('error', 'LRN and Full Name are required fields');
        }
        
        // Keep the full name as is - no splitting needed
        $full_name_clean = trim($full_name);
        
        // Log the name processing for debugging
        log_message('info', 'Name processing - Full Name: "' . $full_name_clean . '"');
        
        // Check if LRN already exists
        $existingStudent = $studentModel->where('lrn', $lrn)->first();
        if ($existingStudent) {
            return redirect()->to('/admin/students/add')->with('error', 'LRN already exists in the system');
        }
        
        // Generate random password
        $password = $this->generateRandomPassword();
        
        // Create student record
        $data = [
            'lrn' => $lrn,
            'full_name' => $full_name_clean,
            'birth_date' => $this->request->getPost('birth_date') ?: null,
            'gender' => $this->request->getPost('gender') ?: null,
            'grade_level' => $this->request->getPost('grade_level') ?: null,
            'enrollment_type' => $this->request->getPost('enrollment_type') ?: 'new',
            'admission_type' => 'regular',
            'previous_school' => $this->request->getPost('previous_school') ?: '',
            'strand_id' => $this->request->getPost('strand_id') ?: null,
            'curriculum_id' => $this->request->getPost('curriculum_id') ?: null,
            'email' => $lrn . '@student.school.edu.ph', // Generate email from LRN
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status' => 'draft'
        ];
        
        // Remove null values to prevent database errors
        $data = array_filter($data, function($value) {
            return $value !== null && $value !== '';
        });
        
        // Clean up string fields (remove extra spaces)
        if (isset($data['full_name'])) {
            $data['full_name'] = trim($data['full_name']);
        }
        
        // Ensure required fields are present
        if (empty($data['full_name'])) {
            log_message('error', 'Full name field missing after processing');
            return redirect()->to('/admin/students/add')->with('error', 'Name processing failed - please check the full name format');
        }
        
        // Validate field length
        if (strlen($data['full_name']) > 255) {
            log_message('error', 'Full name too long: ' . strlen($data['full_name']) . ' characters');
            return redirect()->to('/admin/students/add')->with('error', 'Full name is too long (max 255 characters)');
        }
        
        try {
            // Log the data being inserted for debugging
            log_message('info', 'Attempting to create student with data: ' . json_encode($data));
            log_message('info', 'Data field lengths - full_name: ' . strlen($data['full_name']));
            
            $studentId = $studentModel->insert($data);
            
            if ($studentId) {
                // Store password temporarily in session for display
                session()->setFlashdata('created_password', $password);
                session()->setFlashdata('created_lrn', $lrn);
                
                return redirect()->to('/admin/students')->with('success', 
                    "Student account created successfully! LRN: {$lrn}, Password: {$password}"
                );
            } else {
                log_message('error', 'Student creation failed - insert() returned false');
                return redirect()->to('/admin/students/add')->with('error', 'Failed to create student account - database insert failed');
            }
        } catch (\Exception $e) {
            log_message('error', 'Student creation error: ' . $e->getMessage());
            log_message('error', 'Student creation error trace: ' . $e->getTraceAsString());
            return redirect()->to('/admin/students/add')->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }
    
    private function generateRandomPassword($length = 12)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
    
    public function editStudent($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/students')->with('error', 'Student ID required');
        }
        
        $studentModel = new StudentModel();
        $student = $studentModel->find($id);
        
        if (!$student) {
            return redirect()->to('/admin/students')->with('error', 'Student not found');
        }
        
        if ($this->request->getMethod() === 'POST') {
            // Handle update
            $data = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name' => $this->request->getPost('last_name'),
                'middle_name' => $this->request->getPost('middle_name'),
                'grade_level' => $this->request->getPost('grade_level'),
                'enrollment_type' => $this->request->getPost('enrollment_type'),
                'status' => $this->request->getPost('status')
            ];
            
            try {
                $studentModel->update($id, $data);
                return redirect()->to('/admin/students')->with('success', 'Student updated successfully');
            } catch (\Exception $e) {
                return redirect()->to('/admin/students')->with('error', 'Error updating student: ' . $e->getMessage());
            }
        }
        
        return view('admin/edit_student', ['student' => $student]);
    }
    
    public function updateStudent($id = null)
    {
        return $this->editStudent($id);
    }
    
    public function deleteStudent($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/students')->with('error', 'Student ID required');
        }
        
        $studentModel = new StudentModel();
        
        try {
            $result = $studentModel->delete($id);
            if ($result) {
                return redirect()->to('/admin/students')->with('success', 'Student deleted successfully');
            } else {
                return redirect()->to('/admin/students')->with('error', 'Failed to delete student');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/students')->with('error', 'Error deleting student: ' . $e->getMessage());
        }
    }
    
    public function viewStudent($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/students')->with('error', 'Student ID required');
        }
        
        $studentModel = new StudentModel();
        $student = $studentModel->getStudentWithDetails($id);
        
        if (!$student) {
            return redirect()->to('/admin/students')->with('error', 'Student not found');
        }
        
        return view('admin/view_student', ['student' => $student]);
    }
}
