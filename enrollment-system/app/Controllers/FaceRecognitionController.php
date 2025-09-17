<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubjectModel;
use App\Models\StudentModel;
use App\Models\AttendanceModel;
use App\Models\TeacherModel;

class FaceRecognitionController extends BaseController
{
    public function index()
    {
        $teacherId = session()->get('user_id');
        $teacherModel = new TeacherModel();
        $subjectModel = new SubjectModel();
        
        // Get teacher's assigned subjects for current school year
        $assignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $subjects = [];
        
        if (!empty($assignments['assignments'])) {
            foreach ($assignments['assignments'] as $assignment) {
                $subject = $subjectModel->find($assignment['subject_id']);
                if ($subject) {
                    $subjects[] = [
                        'id' => $subject['id'],
                        'name' => $subject['name'],
                        'code' => $subject['code'],
                        'section_name' => $assignment['section_name']
                    ];
                }
            }
        }
        
        return view('face_recognition/index', [
            'subjects' => $subjects,
            'teacher' => session()->get()
        ]);
    }
    
    public function test()
    {
        return view('face_recognition/test');
    }
    
    public function takeAttendance($subjectId)
    {
        $teacherId = session()->get('user_id');
        $subjectModel = new SubjectModel();
        $teacherModel = new TeacherModel();
        
        // Verify teacher is assigned to this subject
        $assignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $isAssigned = false;
        
        foreach ($assignments['assignments'] as $assignment) {
            if ($assignment['subject_id'] == $subjectId) {
                $isAssigned = true;
                break;
            }
        }
        
        if (!$isAssigned) {
            return redirect()->to('/face-recognition')->with('error', 'You are not assigned to this subject.');
        }
        
        $subject = $subjectModel->find($subjectId);
        if (!$subject) {
            return redirect()->to('/face-recognition')->with('error', 'Subject not found.');
        }
        
        return view('face_recognition/attendance', [
            'subject' => $subject
        ]);
    }
    
    public function processImage()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }
        
        $subjectId = (int)($this->request->getPost('subject_id') ?? 0);
        if ($subjectId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid subject_id']);
        }
        
        $image = $this->request->getFile('image');
        if (!$image || !$image->isValid()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid image file']);
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!in_array($image->getMimeType(), $allowedTypes)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Only JPEG and PNG images are allowed']);
        }
        
        // Validate file size (max 5MB)
        if ($image->getSize() > 5 * 1024 * 1024) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Image size must be less than 5MB']);
        }
        
        try {
            // Save temporary image
            $tempDir = WRITEPATH . 'temp/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $tempFileName = 'face_recognition_' . time() . '_' . $image->getRandomName();
            $tempPath = $tempDir . $tempFileName;
            $image->move($tempDir, $tempFileName);
            
            // Process face recognition
            $result = $this->runFaceRecognition($tempPath, $subjectId);
            
            // Clean up temporary file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            
            return $this->response->setJSON($result);
            
        } catch (\Exception $e) {
            log_message('error', 'Face recognition error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Face recognition processing failed']);
        }
    }
    
    private function runFaceRecognition($imagePath, $subjectId)
    {
        // Get students with face encodings for this subject
        $studentModel = new StudentModel();
        $students = $studentModel->getStudentsWithFaceEncodingsBySubject($subjectId);
        
        if (empty($students)) {
            return [
                'success' => false,
                'error' => 'No students with face encodings found for this subject',
                'recognized_faces' => []
            ];
        }
        
        // Check if Python face recognition is available
        $pythonScript = ROOTPATH . 'face_recognition_app/web_face_recognition.py';
        if (!file_exists($pythonScript)) {
            return [
                'success' => false,
                'error' => 'Python face recognition script not found',
                'recognized_faces' => []
            ];
        }
        
        try {
            // Call Python face recognition script (redirect stderr to null to avoid warnings)
            $command = sprintf(
                'python "%s" "%s" %d 2>nul',
                $pythonScript,
                $imagePath,
                $subjectId
            );
            
            log_message('debug', 'Executing face recognition command: ' . $command);
            
            $output = shell_exec($command);
            
            if ($output === null) {
                throw new \Exception('Python script execution failed');
            }
            
            $result = json_decode($output, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from Python script: ' . $output);
            }
            
            // Add timestamp to recognized faces
            if (isset($result['recognized_faces']) && is_array($result['recognized_faces'])) {
                foreach ($result['recognized_faces'] as &$face) {
                    $face['timestamp'] = date('Y-m-d H:i:s');
                }
            }
            
            log_message('info', 'Face recognition completed: ' . json_encode($result));
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Face recognition error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Face recognition processing failed: ' . $e->getMessage(),
                'recognized_faces' => []
            ];
        }
    }
    
    
    public function recordAttendance()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }
        
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $studentId = (int)($data['student_id'] ?? 0);
        $subjectId = (int)($data['subject_id'] ?? 0);
        
        if ($studentId <= 0 || $subjectId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid student_id or subject_id']);
        }
        
        // Verify teacher is assigned to this subject
        $teacherId = session()->get('user_id');
        $teacherModel = new TeacherModel();
        $assignments = $teacherModel->getTeacherWithAssignments($teacherId);
        $isAssigned = false;
        
        foreach ($assignments['assignments'] as $assignment) {
            if ($assignment['subject_id'] == $subjectId) {
                $isAssigned = true;
                break;
            }
        }
        
        if (!$isAssigned) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'You are not assigned to this subject']);
        }
        
        // Record attendance
        $attendanceModel = new AttendanceModel();
        
        if ($attendanceModel->hasRecordedToday($studentId, $subjectId)) {
            return $this->response->setJSON([
                'status' => 'exists',
                'message' => 'Attendance already recorded for today'
            ]);
        }
        
        if ($attendanceModel->recordAttendance($studentId, $subjectId)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Attendance recorded successfully'
            ]);
        }
        
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to record attendance']);
    }
    
    public function getStudentsForSubject($subjectId)
    {
        $studentModel = new StudentModel();
        $students = $studentModel->getStudentsWithFaceEncodingsBySubject($subjectId);
        
        $result = [];
        foreach ($students as $student) {
            $result[] = [
                'id' => $student['id'],
                'name' => $student['full_name'],
                'lrn' => $student['lrn'],
                'has_face_encoding' => !empty($student['face_encoding'])
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'students' => $result,
            'total' => count($result)
        ]);
    }
    
    public function captureStudentFaces()
    {
        try {
            $teacherId = session()->get('user_id');
            $role = session()->get('role');
            $isLoggedIn = session()->get('is_logged_in');
            
            // Debug logging
            log_message('debug', 'Face capture auth check - Teacher ID: ' . $teacherId . ', Role: ' . $role . ', Logged in: ' . ($isLoggedIn ? 'true' : 'false'));
            
            if (!$teacherId || $role !== 'teacher' || !$isLoggedIn) {
                log_message('error', 'Face capture unauthorized - Teacher ID: ' . $teacherId . ', Role: ' . $role . ', Logged in: ' . ($isLoggedIn ? 'true' : 'false'));
                return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized - Please login as teacher']);
            }
            
            // Get all students from sections assigned to this teacher
            $studentModel = new StudentModel();
            $students = $studentModel->getStudentsByTeacher($teacherId);
            
            return view('face_recognition/capture_faces', [
                'students' => $students,
                'title' => 'Capture Student Faces'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading face capture page: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to load face capture page']);
        }
    }
    
    public function captureFaceForStudent()
    {
        if ($this->request->getMethod() !== 'POST') {
            return $this->response->setStatusCode(405)->setJSON(['error' => 'Method not allowed']);
        }
        
        try {
            $teacherId = session()->get('user_id');
            $role = session()->get('role');
            $isLoggedIn = session()->get('is_logged_in');
            
            // Debug logging
            log_message('debug', 'Face capture POST auth check - Teacher ID: ' . $teacherId . ', Role: ' . $role . ', Logged in: ' . ($isLoggedIn ? 'true' : 'false'));
            
            if (!$teacherId || $role !== 'teacher' || !$isLoggedIn) {
                log_message('error', 'Face capture POST unauthorized - Teacher ID: ' . $teacherId . ', Role: ' . $role . ', Logged in: ' . ($isLoggedIn ? 'true' : 'false'));
                return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized - Please login as teacher']);
            }
            
            $studentId = $this->request->getPost('student_id');
            $imageData = $this->request->getPost('image_data');
            
            if (!$studentId || !$imageData) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing required data']);
            }
            
            // Get student LRN
            $studentModel = new StudentModel();
            $student = $studentModel->find($studentId);
            
            if (!$student) {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Student not found']);
            }
            
            // Save image temporarily
            $tempDir = WRITEPATH . 'temp/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);
            
            $tempImagePath = $tempDir . 'capture_' . $studentId . '_' . time() . '.jpg';
            file_put_contents($tempImagePath, $imageData);
            
            // Process face capture using Python script
            $result = $this->processFaceCapture($tempImagePath, $student['lrn']);
            
            // Clean up temp file
            if (file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }
            
            return $this->response->setJSON($result);
            
        } catch (\Exception $e) {
            log_message('error', 'Face capture error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Face capture failed']);
        }
    }
    
    private function processFaceCapture($imagePath, $lrn)
    {
        // Check if Python face capture script is available
        $pythonScript = ROOTPATH . 'face_recognition_app/capture_faces_web.py';
        if (!file_exists($pythonScript)) {
            return [
                'success' => false,
                'error' => 'Face capture script not available'
            ];
        }
        
        try {
            // Call Python face capture script (redirect stderr to null to avoid warnings)
            $command = sprintf(
                'python "%s" "%s" "%s" 2>nul',
                $pythonScript,
                $imagePath,
                $lrn
            );
            
            log_message('debug', 'Executing face capture command: ' . $command);
            
            $output = shell_exec($command);
            
            if ($output === null) {
                throw new \Exception('Python script execution failed');
            }
            
            $result = json_decode($output, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from Python script: ' . $output);
            }
            
            log_message('info', 'Face capture completed: ' . json_encode($result));
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Face capture error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Face capture processing failed: ' . $e->getMessage()
            ];
        }
    }
    
}
