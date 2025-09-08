<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table = 'attendance_records';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id', 'subject_id', 'recorded_at'];
    protected $useTimestamps = false;

    public function hasRecordedToday(int $studentId, int $subjectId): bool
    {
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');
        return (bool) $this->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->where('recorded_at >=', $start)
            ->where('recorded_at <=', $end)
            ->first();
    }

    public function recordAttendance(int $studentId, int $subjectId): bool
    {
        $data = [
            'student_id' => $studentId,
            'subject_id' => $subjectId,
            'recorded_at' => date('Y-m-d H:i:s'),
        ];
        return (bool) $this->insert($data);
    }
}


