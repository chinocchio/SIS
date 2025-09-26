<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AttendanceModel;

class ApiController extends BaseController
{
    public function recordAttendance()
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();
        $studentId = (int)($data['student_id'] ?? 0);
        $subjectId = (int)($data['subject_id'] ?? 0);
        if ($studentId <= 0 || $subjectId <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid student_id or subject_id']);
        }
        $attendance = new AttendanceModel();
        if ($attendance->hasRecordedToday($studentId, $subjectId)) {
            return $this->response->setJSON(['status' => 'exists', 'message' => 'Already recorded today']);
        }
        if ($attendance->recordAttendance($studentId, $subjectId)) {
            return $this->response->setJSON(['status' => 'ok']);
        }
        return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to record attendance']);
    }

    public function getActiveSession()
    {
        // TODO: later derive from teacher session/assignment; return a stub for now
        $subjectId = (int)($this->request->getGet('subject_id') ?? 0);
        if ($subjectId > 0) {
            return $this->response->setJSON(['subject_id' => $subjectId]);
        }
        // Default none
        return $this->response->setStatusCode(404)->setJSON(['error' => 'No active session']);
    }
}


