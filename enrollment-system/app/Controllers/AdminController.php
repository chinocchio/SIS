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
use App\Models\TeacherModel;
use App\Models\SectionModel;
use App\Libraries\OcrService;
use App\Models\DocumentModel;
use App\Models\UserModel;

class AdminController extends BaseController
{
    public function __construct()
    {
        // Additional security check - ensure user is logged in and is admin
        if (!session()->get('is_logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/auth/login')->with('error', 'Please login as admin to access this page.');
        }
    }
    
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
        $strandModel = new StrandModel();
        
        try {
            // Debug: Check what's in the database
            $allSubjects = $subjectModel->findAll();
            log_message('info', 'Total subjects in database: ' . count($allSubjects));
            
            // Try the JOIN method first
            try {
                $subjects = $subjectModel->getAllActiveSubjectsWithCurriculumAndStrand();
                log_message('info', 'Subjects after JOIN query: ' . count($subjects));
            } catch (\Exception $joinError) {
                log_message('error', 'JOIN query failed: ' . $joinError->getMessage());
                $subjects = [];
            }
            
            // If JOIN query returns no results or fails, try fallback method
            if (empty($subjects)) {
                log_message('info', 'JOIN query returned no results, trying fallback method');
                $subjects = $subjectModel->getAllActiveSubjectsSimple();
                log_message('info', 'Subjects from fallback method: ' . count($subjects));
                
                // Manually populate curriculum and strand names
                if (!empty($subjects)) {
                    foreach ($subjects as &$subject) {
                        if (!empty($subject['curriculum_id'])) {
                            $curriculum = $curriculumModel->find($subject['curriculum_id']);
                            $subject['curriculum_name'] = $curriculum ? $curriculum['name'] : 'Unknown';
                        } else {
                            $subject['curriculum_name'] = null;
                        }
                        
                        if (!empty($subject['strand_id'])) {
                            $strand = $strandModel->find($subject['strand_id']);
                            $subject['strand_name'] = $strand ? $strand['name'] : 'Unknown';
                        } else {
                            $subject['strand_name'] = null;
                        }
                    }
                }
            }
            
            $curriculums = $curriculumModel->getAllActiveCurriculums();
            log_message('info', 'Curriculums found: ' . count($curriculums));
            
            $strands = $strandModel->getActiveStrandsWithTracks();
            log_message('info', 'Strands found: ' . count($strands));
            
            $data['subjects'] = $subjects;
            $data['curriculums'] = $curriculums;
            $data['strands'] = $strands;
            
            // Debug: Log first few subjects
            if (!empty($subjects)) {
                log_message('info', 'First subject data: ' . json_encode(array_slice($subjects, 0, 1)));
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error in manageSubjects: ' . $e->getMessage());
            $data['subjects'] = [];
            $data['curriculums'] = [];
            $data['strands'] = [];
            $data['error'] = 'Error loading subjects: ' . $e->getMessage();
        }
        
        return view('admin/manage_subjects', $data);
    }
    
    public function showAddSubjectForm()
    {
        $curriculumModel = new CurriculumModel();
        $strandModel = new StrandModel();
        
        $data = [
            'curriculums' => $curriculumModel->getAllActiveCurriculums(),
            'strands' => $strandModel->getActiveStrandsWithTracks()
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
        $subjectType = $this->request->getPost('subject_type');
        $curriculumId = $this->request->getPost('curriculum_id');
        $strandId = $this->request->getPost('strand_id');
        $name = $this->request->getPost('name');
        $description = $this->request->getPost('description');
        $gradeLevel = $this->request->getPost('grade_level_shs'); // Only for SHS
        $semester = $this->request->getPost('semester'); // Now required for SHS
        $shsSubjectCategory = $this->request->getPost('shs_subject_category'); // New field for SHS
        $isActive = $this->request->getPost('is_active') ? 1 : 0;
        
        // Debug: Log the extracted data
        log_message('info', 'Extracted data - subject_type: ' . $subjectType . ', curriculum_id: ' . $curriculumId . ', strand_id: ' . $strandId . ', name: ' . $name . ', grade_level: ' . $gradeLevel . ', semester: ' . $semester . ', shs_subject_category: ' . $shsSubjectCategory);
        
        // Basic validation
        if (empty($subjectType) || empty($name)) {
            log_message('error', 'Validation failed - missing required fields');
            return redirect()->to('/admin/subjects')->with('error', 'Subject type and name are required.');
        }
        
        // Validate subject type specific fields
        if ($subjectType === 'jhs' && empty($curriculumId)) {
            log_message('error', 'Validation failed - curriculum required for JHS');
            return redirect()->to('/admin/subjects')->with('error', 'Curriculum is required for JHS subjects.');
        }
        
        if ($subjectType === 'shs') {
            if (empty($strandId) || empty($gradeLevel) || empty($semester) || empty($shsSubjectCategory)) {
                log_message('error', 'Validation failed - missing SHS required fields');
                return redirect()->to('/admin/subjects')->with('error', 'For SHS subjects: strand, grade level, semester, and subject category are required.');
            }
        }
        
        try {
            if ($subjectType === 'jhs') {
                // For JHS: Create subjects for all grade levels (7-10) and all quarters (1-4)
                log_message('info', 'Creating JHS subjects for all grade levels and quarters');
                
                $jhsGrades = [7, 8, 9, 10];
                $quarters = [1, 2, 3, 4];
                $createdCount = 0;
                
                foreach ($jhsGrades as $grade) {
                    foreach ($quarters as $q) {
                        // Generate a unique code for each grade-quarter combination
                        $subjectCode = strtoupper(substr($name, 0, 3)) . $grade . 'Q' . $q;
                        
                        // Check if this subject already exists
                        if (!$subjectModel->isCodeUniqueInCurriculum($subjectCode, $curriculumId, $grade, null, $q)) {
                            log_message('warning', 'Subject already exists for grade ' . $grade . ' quarter ' . $q . ' - skipping');
                            continue;
                        }
                        
                        // Prepare data for JHS subject
                        $data = [
                            'curriculum_id' => $curriculumId,
                            'strand_id' => null,
                            'code' => $subjectCode,
                            'name' => trim($name),
                            'description' => trim($description),
                            'units' => 1.0, // Default units for JHS
                            'grade_level' => $grade,
                            'semester' => null, // No semester for JHS
                            'quarter' => $q,
                            'is_core' => 'core', // All JHS subjects are core
                            'is_active' => $isActive
                        ];
                        
                        // Insert JHS subject
                        $result = $subjectModel->insert($data);
                        if ($result) {
                            $createdCount++;
                            log_message('info', 'Created JHS subject: ' . $subjectCode . ' for grade ' . $grade . ' quarter ' . $q);
                        }
                    }
                }
                
                if ($createdCount > 0) {
                    log_message('info', 'Successfully created ' . $createdCount . ' JHS subjects');
                    return redirect()->to('/admin/subjects')->with('success', 'Successfully created ' . $createdCount . ' JHS subjects for all grade levels and quarters!');
                } else {
                    log_message('warning', 'No new JHS subjects were created (all already exist)');
                    return redirect()->to('/admin/subjects')->with('warning', 'All subjects for this curriculum already exist.');
                }
                
            } else {
                // For SHS: Create subjects for both quarters in the selected semester
                log_message('info', 'Creating SHS subjects for semester ' . $semester);
                
                // Map subject category to is_core field
                $isCore = $shsSubjectCategory; // Use the enum value directly
                
                // Determine quarters based on semester
                $quarters = ($semester == 1) ? [1, 2] : [3, 4];
                $createdCount = 0;
                
                foreach ($quarters as $quarter) {
                    // Generate a unique code for each quarter
                    $subjectCode = strtoupper(substr($name, 0, 3)) . $gradeLevel . 'S' . $semester . 'Q' . $quarter;
                    
                    // Check if subject code is unique within the strand and grade/semester/quarter
                    if (!$subjectModel->isCodeUniqueInStrand($subjectCode, $strandId, $gradeLevel, $semester, $quarter)) {
                        log_message('warning', 'Subject already exists: ' . $subjectCode . ' in grade ' . $gradeLevel . ' semester ' . $semester . ' quarter ' . $quarter . ' - skipping');
                        continue;
                    }
                    
                    // Prepare data for SHS subject
                    $data = [
                        'curriculum_id' => null,
                        'strand_id' => $strandId,
                        'code' => $subjectCode,
                        'name' => trim($name),
                        'description' => trim($description),
                        'units' => 1.0, // Default units for SHS
                        'grade_level' => $gradeLevel,
                        'semester' => $semester,
                        'quarter' => $quarter,
                        'is_core' => $isCore,
                        'is_active' => $isActive
                    ];
                    
                    // Debug: Log the prepared data
                    log_message('info', 'Prepared SHS data for insert (Q' . $quarter . '): ' . json_encode($data));
                    
                    // Insert SHS subject
                    $result = $subjectModel->insert($data);
                    if ($result) {
                        $createdCount++;
                        log_message('info', 'SHS subject created successfully for Q' . $quarter . ' with ID: ' . $result);
                    }
                }
                
                if ($createdCount > 0) {
                    log_message('info', 'Successfully created ' . $createdCount . ' SHS subjects for semester ' . $semester);
                    return redirect()->to('/admin/subjects')->with('success', 'Successfully created ' . $createdCount . ' SHS subjects for semester ' . $semester . ' (quarters ' . implode(', ', $quarters) . ')!');
                } else {
                    log_message('warning', 'No new SHS subjects were created (all already exist)');
                    return redirect()->to('/admin/subjects')->with('warning', 'All subjects for this strand, grade level, and semester already exist.');
                }
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
            $isCore = $this->request->getPost('is_core') ?? 'core';
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
    
    // ==================== REGISTRAR MANAGEMENT METHODS ====================
    
    public function manageRegistrars()
    {
        $userModel = new UserModel();
        $search = $this->request->getGet('search');
        
        if ($search) {
            $registrars = $userModel->where('role', 'registrar')
                                   ->like('first_name', $search)
                                   ->orLike('last_name', $search)
                                   ->orLike('username', $search)
                                   ->orLike('email', $search)
                                   ->findAll();
        } else {
            $registrars = $userModel->where('role', 'registrar')->findAll();
        }
        
        // Get registrar counts for summary
        $totalRegistrars = $userModel->where('role', 'registrar')->countAllResults();
        $activeRegistrars = $userModel->where('role', 'registrar')->where('is_active', 1)->countAllResults();
        $inactiveRegistrars = $userModel->where('role', 'registrar')->where('is_active', 0)->countAllResults();
        
        $data = [
            'registrars' => $registrars,
            'totalRegistrars' => $totalRegistrars,
            'activeRegistrars' => $activeRegistrars,
            'inactiveRegistrars' => $inactiveRegistrars
        ];
        
        // Ensure correct content type for browsers to render HTML
        return $this->response
            ->setContentType('text/html')
            ->setBody(view('admin/manage_registrars', $data));
    }
    
    public function showAddRegistrarForm()
    {
        return view('admin/add_registrar');
    }
    
    public function createRegistrar()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/registrars/add')->with('error', 'Invalid request method');
        }
        
        $userModel = new UserModel();
        
        // Validate required fields
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $first_name = $this->request->getPost('first_name');
        $last_name = $this->request->getPost('last_name');
        $email = $this->request->getPost('email');
        
        if (empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($email)) {
            return redirect()->to('/admin/registrars/add')->with('error', 'All fields are required');
        }
        
        // Check if username already exists
        $existingUser = $userModel->where('username', $username)->first();
        if ($existingUser) {
            return redirect()->to('/admin/registrars/add')->with('error', 'Username already exists in the system');
        }
        
        // Check if email already exists
        $existingEmail = $userModel->where('email', $email)->first();
        if ($existingEmail) {
            return redirect()->to('/admin/registrars/add')->with('error', 'Email already exists in the system');
        }
        
        // Create registrar record
        $data = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'role' => 'registrar',
            'is_active' => 1
        ];
        
        try {
            $registrarId = $userModel->insert($data);
            
            if ($registrarId) {
                return redirect()->to('/admin/registrars')->with('success', 
                    "Registrar account created successfully! Username: {$username}, Password: {$password}"
                );
            } else {
                return redirect()->to('/admin/registrars/add')->with('error', 'Failed to create registrar account');
            }
        } catch (\Exception $e) {
            log_message('error', 'Registrar creation error: ' . $e->getMessage());
            return redirect()->to('/admin/registrars/add')->with('error', 'Error creating registrar: ' . $e->getMessage());
        }
    }
    
    public function editRegistrar($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/registrars')->with('error', 'Registrar ID required');
        }
        
        $userModel = new UserModel();
        $registrar = $userModel->where('role', 'registrar')->find($id);
        
        if (!$registrar) {
            return redirect()->to('/admin/registrars')->with('error', 'Registrar not found');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $first_name = $this->request->getPost('first_name');
            $last_name = $this->request->getPost('last_name');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $is_active = $this->request->getPost('is_active') ? 1 : 0;
            
            if (empty($first_name) || empty($last_name) || empty($email)) {
                return redirect()->to('/admin/registrars/edit/' . $id)->with('error', 'First name, last name, and email are required');
            }
            
            // Check if email already exists for other users
            $existingEmail = $userModel->where('email', $email)->where('id !=', $id)->first();
            if ($existingEmail) {
                return redirect()->to('/admin/registrars/edit/' . $id)->with('error', 'Email already exists for another user');
            }
            
            $updateData = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'is_active' => $is_active
            ];
            
            // Only update password if provided
            if (!empty($password)) {
                $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            try {
                $userModel->update($id, $updateData);
                return redirect()->to('/admin/registrars')->with('success', 'Registrar updated successfully');
            } catch (\Exception $e) {
                log_message('error', 'Registrar update error: ' . $e->getMessage());
                return redirect()->to('/admin/registrars/edit/' . $id)->with('error', 'Error updating registrar: ' . $e->getMessage());
            }
        }
        
        return view('admin/edit_registrar', ['registrar' => $registrar]);
    }
    
    public function deleteRegistrar($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/registrars')->with('error', 'Registrar ID required');
        }
        
        $userModel = new UserModel();
        $registrar = $userModel->where('role', 'registrar')->find($id);
        
        if (!$registrar) {
            return redirect()->to('/admin/registrars')->with('error', 'Registrar not found');
        }
        
        try {
            $userModel->delete($id);
            return redirect()->to('/admin/registrars')->with('success', 'Registrar deleted successfully');
        } catch (\Exception $e) {
            log_message('error', 'Registrar deletion error: ' . $e->getMessage());
            return redirect()->to('/admin/registrars')->with('error', 'Error deleting registrar: ' . $e->getMessage());
        }
    }
    
    public function viewRegistrar($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/registrars')->with('error', 'Registrar ID required');
        }
        
        $userModel = new UserModel();
        $registrar = $userModel->where('role', 'registrar')->find($id);
        
        if (!$registrar) {
            return redirect()->to('/admin/registrars')->with('error', 'Registrar not found');
        }
        
        return view('admin/view_registrar', ['registrar' => $registrar]);
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
            'status' => 'pending'
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
        
        // Fetch submitted documents for this student
        $documents = [];
        $approvedByName = null;
        $grades = [];
        
        try {
            $documentModel = new DocumentModel();
            $documents = $documentModel->getDocumentsByStudent((int)$id);
            
            // Resolve approver name if available
            if (!empty($student['approved_by'])) {
                $userModel = new UserModel();
                $approver = $userModel->find((int)$student['approved_by']);
                if ($approver) {
                    $approvedByName = trim(($approver['first_name'] ?? '') . ' ' . ($approver['last_name'] ?? '')) ?: ($approver['username'] ?? null);
                }
            }
            
            // Fetch recorded grades from student_grades table
            $schoolYearModel = new SchoolYearModel();
            $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
            
            if ($activeSchoolYear) {
                $db = \Config\Database::connect();
                $gradesQuery = $db->table('student_grades sg')
                                ->select('sg.*, s.name as subject_name, s.code as subject_code, s.grade_level, s.quarter, s.is_core')
                                ->join('subjects s', 's.id = sg.subject_id')
                                ->where('sg.student_id', $id)
                                ->where('sg.school_year_id', $activeSchoolYear['id'])
                                ->get();
                
                $grades = $gradesQuery->getResultArray();
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error fetching data for student ' . $id . ': ' . $e->getMessage());
        }
        
        if (!$student) {
            return redirect()->to('/admin/students')->with('error', 'Student not found');
        }
        
        return view('admin/view_student', [
            'student' => $student, 
            'documents' => $documents, 
            'approvedByName' => $approvedByName,
            'grades' => $grades
        ]);
    }

    public function approveStudent($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/students')->with('error', 'Student ID required');
        }
        
        $studentModel = new StudentModel();
        $student = $studentModel->find($id);
        if (!$student) {
            return redirect()->to('/admin/students')->with('error', 'Student not found');
        }
        
        try {
            $approvedBy = session()->get('user_id') ?? session()->get('admin_id') ?? null;
            $updateData = ['status' => 'approved'];
            if ($approvedBy !== null) {
                $updateData['approved_by'] = $approvedBy; // Will be ignored if column doesn't exist
            }
            try {
                $studentModel->update($id, $updateData);
            } catch (\Exception $e) {
                // Retry without approved_by in case column doesn't exist
                unset($updateData['approved_by']);
                $studentModel->update($id, $updateData);
            }
            return redirect()->to('/admin/students/view/' . $id)->with('success', 'Student approved successfully.');
        } catch (\Exception $e) {
            return redirect()->to('/admin/students/view/' . $id)->with('error', 'Failed to approve student: ' . $e->getMessage());
        }
    }
    
    public function saveStudentGrade()
    {
        // Check if this is an AJAX request
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }
        
        // Get JSON data
        $jsonData = $this->request->getJSON(true);
        
        if (!$jsonData) {
            return $this->response->setJSON(['success' => false, 'message' => 'No data received']);
        }
        
        $studentId = $jsonData['student_id'] ?? null;
        $subjectId = $jsonData['subject_id'] ?? null;
        $quarter = $jsonData['quarter'] ?? null;
        $grade = $jsonData['grade'] ?? null;
        
        // Validation
        if (!$studentId || !$subjectId || !$quarter || !$grade) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing required data']);
        }
        
        if ($grade < 75 || $grade > 100) {
            return $this->response->setJSON(['success' => false, 'message' => 'Grade must be between 75 and 100']);
        }
        
        try {
            // Get current school year
            $schoolYearModel = new \App\Models\SchoolYearModel();
            $currentSchoolYear = $schoolYearModel->getActiveSchoolYear();
            
            if (!$currentSchoolYear) {
                return $this->response->setJSON(['success' => false, 'message' => 'No active school year found']);
            }
            
            // Save or update the grade
            $studentGradeModel = new \App\Models\StudentGradeModel();
            $gradeData = [
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'school_year_id' => $currentSchoolYear['id'],
                'quarter' => $quarter,
                'grade' => $grade,
                'remarks' => '',
                'is_final' => 1
            ];
            
            $result = $studentGradeModel->updateOrCreateGrade($gradeData);
            
            if ($result) {
                return $this->response->setJSON(['success' => true, 'message' => 'Grade saved successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to save grade']);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Error saving student grade: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error saving grade: ' . $e->getMessage()]);
        }
    }

    // Section Management Methods
    public function manageSections()
    {
        $sectionModel = new \App\Models\SectionModel();
        $schoolYearModel = new SchoolYearModel();
        
        $currentSchoolYear = $schoolYearModel->getActiveSchoolYear();
        $schoolYearId = $this->request->getGet('school_year_id') ?? ($currentSchoolYear['id'] ?? null);
        
        $sections = [];
        if ($schoolYearId) {
            $sections = $sectionModel->getSectionsSummary($schoolYearId);
        }
        
        $data = [
            'sections' => $sections,
            'schoolYears' => $schoolYearModel->findAll(),
            'currentSchoolYear' => $currentSchoolYear,
            'selectedSchoolYear' => $schoolYearId,
            'totalSections' => count($sections),
            'totalStudents' => array_sum(array_column($sections, 'student_count'))
        ];
        
        return view('admin/manage_sections', $data);
    }

    public function showAddSectionForm()
    {
        $schoolYearModel = new SchoolYearModel();
        $strandModel = new StrandModel();
        
        $data = [
            'schoolYears' => $schoolYearModel->findAll(),
            'strands' => $strandModel->findAll()
        ];
        
        return view('admin/add_section', $data);
    }

    public function createSection()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/sections');
        }
        
        $sectionModel = new \App\Models\SectionModel();
        
        $data = [
            'name' => $this->request->getPost('name'),
            'grade_level' => $this->request->getPost('grade_level'),
            'strand_id' => $this->request->getPost('strand_id') ?: null,
            'school_year_id' => $this->request->getPost('school_year_id'),
            'capacity_min' => $this->request->getPost('capacity_min') ?: 35,
            'capacity_max' => $this->request->getPost('capacity_max') ?: 40
        ];
        
        // Validate section name uniqueness in school year
        if (!$sectionModel->isNameUniqueInSchoolYear($data['name'], $data['school_year_id'])) {
            return redirect()->back()->withInput()->with('error', 'Section name already exists for this school year.');
        }
        
        if ($sectionModel->insert($data)) {
            return redirect()->to('/admin/sections')->with('success', 'Section created successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create section. Please check your input.');
        }
    }

    public function editSection($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/sections')->with('error', 'Section ID required');
        }
        
        $sectionModel = new \App\Models\SectionModel();
        $schoolYearModel = new SchoolYearModel();
        $strandModel = new StrandModel();
        
        $section = $sectionModel->getSectionWithDetails($id);
        if (!$section) {
            return redirect()->to('/admin/sections')->with('error', 'Section not found');
        }
        
        if ($this->request->getMethod() === 'POST') {
            $updateData = [
                'name' => $this->request->getPost('name'),
                'grade_level' => $this->request->getPost('grade_level'),
                'strand_id' => $this->request->getPost('strand_id') ?: null,
                'school_year_id' => $this->request->getPost('school_year_id'),
                'capacity_min' => $this->request->getPost('capacity_min') ?: 35,
                'capacity_max' => $this->request->getPost('capacity_max') ?: 40
            ];
            
            // Validate section name uniqueness in school year (excluding current section)
            if (!$sectionModel->isNameUniqueInSchoolYear($updateData['name'], $updateData['school_year_id'], $id)) {
                return redirect()->back()->withInput()->with('error', 'Section name already exists for this school year.');
            }
            
            if ($sectionModel->update($id, $updateData)) {
                return redirect()->to('/admin/sections')->with('success', 'Section updated successfully.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to update section. Please check your input.');
            }
        }
        
        $data = [
            'section' => $section,
            'schoolYears' => $schoolYearModel->findAll(),
            'strands' => $strandModel->findAll()
        ];
        
        return view('admin/edit_section', $data);
    }

    public function deleteSection($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/sections')->with('error', 'Section ID required');
        }
        
        $sectionModel = new \App\Models\SectionModel();
        
        // Check if section has students
        $capacity = $sectionModel->getSectionCapacity($id);
        if ($capacity['current'] > 0) {
            return redirect()->to('/admin/sections')->with('error', 'Cannot delete section with assigned students. Please reassign students first.');
        }
        
        try {
            if ($sectionModel->delete($id)) {
                return redirect()->to('/admin/sections')->with('success', 'Section deleted successfully.');
            } else {
                return redirect()->to('/admin/sections')->with('error', 'Failed to delete section.');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/sections')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function viewSection($id = null)
    {
        if (!$id) {
            return redirect()->to('/admin/sections')->with('error', 'Section ID required');
        }
        
        $sectionModel = new \App\Models\SectionModel();
        $studentModel = new StudentModel();
        
        $section = $sectionModel->getSectionWithDetails($id);
        if (!$section) {
            return redirect()->to('/admin/sections')->with('error', 'Section not found');
        }
        
        // Get students in this section
        $students = $studentModel->where('section_id', $id)
                               ->orderBy('full_name', 'ASC')
                               ->findAll();
        
        $capacity = $sectionModel->getSectionCapacity($id);
        
        $data = [
            'section' => $section,
            'students' => $students,
            'capacity' => $capacity
        ];
        
        return view('admin/view_section', $data);
    }

    public function assignStudentToSection()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/students')->with('error', 'Invalid request method');
        }
        
        $studentId = $this->request->getPost('student_id');
        $sectionId = $this->request->getPost('section_id');
        
        if (!$studentId || !$sectionId) {
            return redirect()->back()->with('error', 'Student ID and Section ID are required');
        }
        
        $studentModel = new StudentModel();
        $sectionModel = new \App\Models\SectionModel();
        
        // Check if student exists
        $student = $studentModel->find($studentId);
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }
        
        // Check if section exists
        $section = $sectionModel->find($sectionId);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        
        // Check if section has capacity
        $capacity = $sectionModel->getSectionCapacity($sectionId);
        if ($capacity['current'] >= $capacity['max']) {
            return redirect()->back()->with('error', 'Section is at maximum capacity');
        }
        
        // Check if student is already assigned to a section
        if ($student['section_id']) {
            // Store current section as previous section before updating
            $updateData = [
                'previous_section_id' => $student['section_id'],
                'section_id' => $sectionId
            ];
        } else {
            $updateData = ['section_id' => $sectionId];
        }
        
        if ($studentModel->update($studentId, $updateData)) {
            return redirect()->back()->with('success', 'Student assigned to section successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to assign student to section');
        }
    }

    public function removeStudentFromSection($studentId = null)
    {
        if (!$studentId) {
            return redirect()->to('/admin/students')->with('error', 'Student ID required');
        }
        
        $studentModel = new StudentModel();
        
        $student = $studentModel->find($studentId);
        if (!$student) {
            return redirect()->to('/admin/students')->with('error', 'Student not found');
        }
        
        if (!$student['section_id']) {
            return redirect()->back()->with('error', 'Student is not assigned to any section');
        }
        
        // Store current section as previous section and remove current assignment
        $updateData = [
            'previous_section_id' => $student['section_id'],
            'section_id' => null
        ];
        
        if ($studentModel->update($studentId, $updateData)) {
            return redirect()->back()->with('success', 'Student removed from section successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to remove student from section');
        }
    }
    
    // Teacher Management Methods
    public function manageTeachers()
    {
        $teacherModel = new TeacherModel();
        $teachers = $teacherModel->getTeachersWithAssignments();
        
        $data = [
            'teachers' => $teachers
        ];
        
        return view('admin/manage_teachers', $data);
    }
    
    public function showAddTeacherForm()
    {
        return view('admin/add_teacher');
    }
    
    public function createTeacher()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/teachers/add')->with('error', 'Invalid request method');
        }
        
        $teacherModel = new TeacherModel();
        
        // Get form data
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'is_active' => 1
        ];
        
        // Validate required fields
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['username']) || empty($data['email']) || empty($this->request->getPost('password'))) {
            return redirect()->to('/admin/teachers/add')->with('error', 'First Name, Last Name, Username, Email, and Password are required');
        }
        
        // Check if username already exists
        if ($teacherModel->where('username', $data['username'])->first()) {
            return redirect()->to('/admin/teachers/add')->with('error', 'Username already exists in the system');
        }
        
        // Check if email already exists
        if ($teacherModel->where('email', $data['email'])->first()) {
            return redirect()->to('/admin/teachers/add')->with('error', 'Email already exists in the system');
        }
        
        try {
            if ($teacherModel->insert($data)) {
                return redirect()->to('/admin/teachers')->with('success', 'Teacher created successfully!');
            } else {
                return redirect()->to('/admin/teachers/add')->with('error', 'Failed to create teacher. Please check your input.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Teacher creation error: ' . $e->getMessage());
            return redirect()->to('/admin/teachers/add')->with('error', 'Error creating teacher: ' . $e->getMessage());
        }
    }
    
    public function viewTeacher($teacherId)
    {
        if (!$teacherId) {
            return redirect()->to('/admin/teachers')->with('error', 'Teacher ID required');
        }
        
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->getTeacherWithAssignments($teacherId);
        
        if (!$teacher) {
            return redirect()->to('/admin/teachers')->with('error', 'Teacher not found');
        }
        
        // Get available subjects and sections for assignment
        $subjectModel = new SubjectModel();
        $sectionModel = new SectionModel();
        $schoolYearModel = new SchoolYearModel();
        
        $availableSubjects = $teacherModel->getAvailableSubjects($teacherId);
        $sections = $sectionModel->getSectionsSummary();
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        $data = [
            'teacher' => $teacher,
            'availableSubjects' => $availableSubjects,
            'sections' => $sections,
            'activeSchoolYear' => $activeSchoolYear
        ];
        
        return view('admin/view_teacher', $data);
    }
    
    public function showEditTeacherForm($teacherId)
    {
        if (!$teacherId) {
            return redirect()->to('/admin/teachers')->with('error', 'Teacher ID required');
        }
        
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->find($teacherId);
        
        if (!$teacher) {
            return redirect()->to('/admin/teachers')->with('error', 'Teacher not found');
        }
        
        return view('admin/edit_teacher', ['teacher' => $teacher]);
    }
    
    public function updateTeacher($teacherId)
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/admin/teachers/edit/' . $teacherId)->with('error', 'Invalid request method');
        }
        
        if (!$teacherId) {
            return redirect()->to('/admin/teachers')->with('error', 'Teacher ID required');
        }
        
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->find($teacherId);
        
        if (!$teacher) {
            return redirect()->to('/admin/teachers')->with('error', 'Teacher not found');
        }
        
        // Get form data
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];
        
        // Validate required fields
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['username']) || empty($data['email'])) {
            return redirect()->to('/admin/teachers/edit/' . $teacherId)->with('error', 'First Name, Last Name, Username, and Email are required');
        }
        
        // Check if username already exists (excluding current teacher)
        $existingTeacher = $teacherModel->where('username', $data['username'])->where('id !=', $teacherId)->first();
        if ($existingTeacher) {
            return redirect()->to('/admin/teachers/edit/' . $teacherId)->with('error', 'Username already exists in the system');
        }
        
        // Check if email already exists (excluding current teacher)
        $existingTeacher = $teacherModel->where('email', $data['email'])->where('id !=', $teacherId)->first();
        if ($existingTeacher) {
            return redirect()->to('/admin/teachers/edit/' . $teacherId)->with('error', 'Email already exists in the system');
        }
        
        // Update password if provided
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }
        
        try {
            if ($teacherModel->update($teacherId, $data)) {
                return redirect()->to('/admin/teachers')->with('success', 'Teacher updated successfully!');
            } else {
                return redirect()->to('/admin/teachers/edit/' . $teacherId)->with('error', 'Failed to update teacher. Please check your input.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Teacher update error: ' . $e->getMessage());
            return redirect()->to('/admin/teachers/edit/' . $teacherId)->with('error', 'Error updating teacher: ' . $e->getMessage());
        }
    }
    
    public function deleteTeacher($teacherId)
    {
        if (!$teacherId) {
            return redirect()->to('/admin/teachers')->with('error', 'Teacher ID required');
        }
        
        $teacherModel = new TeacherModel();
        
        try {
            $result = $teacherModel->delete($teacherId);
            if ($result) {
                return redirect()->to('/admin/teachers')->with('success', 'Teacher deleted successfully');
            } else {
                return redirect()->to('/admin/teachers')->with('error', 'Failed to delete teacher');
            }
        } catch (\Exception $e) {
            return redirect()->to('/admin/teachers')->with('error', 'Error deleting teacher: ' . $e->getMessage());
        }
    }
    
    public function assignSubjectToTeacher()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Invalid request method.');
        }
        
        $teacherId = $this->request->getPost('teacher_id');
        $subjectId = $this->request->getPost('subject_id');
        $sectionId = $this->request->getPost('section_id');
        $schoolYearId = $this->request->getPost('school_year_id');
        
        if (!$teacherId || !$subjectId || !$sectionId || !$schoolYearId) {
            return redirect()->back()->with('error', 'All fields are required.');
        }
        
        $teacherModel = new TeacherModel();
        
        try {
            $result = $teacherModel->assignSubjectToTeacher($teacherId, $subjectId, $sectionId, $schoolYearId);
            if ($result) {
                return redirect()->back()->with('success', 'Subject assigned to teacher successfully.');
            } else {
                return redirect()->back()->with('error', 'Subject assignment already exists.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Subject assignment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign subject to teacher.');
        }
    }
    
    public function removeSubjectAssignment($assignmentId)
    {
        if (!$assignmentId) {
            return redirect()->back()->with('error', 'Assignment ID required');
        }
        
        $teacherModel = new TeacherModel();
        
        try {
            $result = $teacherModel->removeSubjectAssignment($assignmentId);
            if ($result) {
                return redirect()->back()->with('success', 'Subject assignment removed successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to remove subject assignment.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error removing assignment: ' . $e->getMessage());
        }
    }
    
    public function assignTeachers()
    {
        $teacherModel = new TeacherModel();
        $subjectModel = new SubjectModel();
        $sectionModel = new SectionModel();
        $schoolYearModel = new SchoolYearModel();
        
        // Get all data needed for the assignment page
        $teachers = $teacherModel->where('is_active', 1)->findAll() ?? [];
        $subjects = $subjectModel->getSubjectsWithCurriculum() ?? [];
        $sections = $sectionModel->getSectionsSummary() ?? [];
        $activeSchoolYear = $schoolYearModel->getActiveSchoolYear();
        
        // Get all current assignments
        $assignments = $teacherModel->getAllAssignments() ?? [];
        
        // Calculate statistics
        $totalTeachers = count($teachers);
        $totalAssignments = count($assignments);
        $totalSubjects = count($subjects);
        $totalSections = count($sections);
        
        $data = [
            'teachers' => $teachers,
            'subjects' => $subjects,
            'sections' => $sections,
            'activeSchoolYear' => $activeSchoolYear,
            'assignments' => $assignments,
            'totalTeachers' => $totalTeachers,
            'totalAssignments' => $totalAssignments,
            'totalSubjects' => $totalSubjects,
            'totalSections' => $totalSections
        ];
        
        return view('admin/assign_teachers', $data);
    }
    
    public function getSubjectsByGradeLevel()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setJSON(['error' => 'Invalid request method']);
        }
        
        $gradeLevel = $this->request->getPost('grade_level');
        
        if (!$gradeLevel) {
            return $this->response->setJSON(['error' => 'Grade level required']);
        }
        
        $subjectModel = new SubjectModel();
        $subjects = $subjectModel->getSubjectsWithCurriculum();
        
        // Filter subjects by grade level
        $filteredSubjects = array_filter($subjects, function($subject) use ($gradeLevel) {
            return $subject['grade_level'] == $gradeLevel;
        });
        
        return $this->response->setJSON([
            'success' => true,
            'subjects' => array_values($filteredSubjects)
        ]);
    }
}
