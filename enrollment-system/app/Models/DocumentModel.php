<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $allowedFields = ['student_id', 'document_type', 'file_path', 'status', 'uploaded_at'];
    public $useTimestamps = false;

    public function getDocumentsByStudent(int $studentId)
    {
        return $this->where('student_id', $studentId)
                    ->orderBy('uploaded_at', 'DESC')
                    ->findAll();
    }

    public function approveDocument(int $documentId)
    {
        return $this->update($documentId, ['status' => 'approved']);
    }

    public function rejectDocument(int $documentId)
    {
        return $this->update($documentId, ['status' => 'rejected']);
    }
}


