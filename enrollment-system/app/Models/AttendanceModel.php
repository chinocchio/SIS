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

    public function getStudentAttendance(int $studentId, int $limit = 50)
    {
        $db = \Config\Database::connect();
        return $db->table('attendance_records ar')
            ->select('ar.*, s.name as subject_name, s.code as subject_code')
            ->join('subjects s', 's.id = ar.subject_id')
            ->where('ar.student_id', $studentId)
            ->orderBy('ar.recorded_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    public function getTeacherAttendance(int $teacherId, int $limit = 100)
    {
        $db = \Config\Database::connect();
        return $db->table('attendance_records ar')
            ->select('ar.*, s.name as subject_name, s.code as subject_code, st.full_name as student_name, st.lrn')
            ->join('subjects s', 's.id = ar.subject_id')
            ->join('students st', 'st.id = ar.student_id')
            ->join('teacher_subject_assignments tsa', 'tsa.subject_id = ar.subject_id')
            ->where('tsa.teacher_id', $teacherId)
            ->where('tsa.is_active', 1)
            ->orderBy('ar.recorded_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
}


