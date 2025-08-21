<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\StudentModel;
use App\Models\DocumentModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class StudentController extends BaseController
{
    public function dashboard()
    {
        $studentModel = new StudentModel();
        $student = $studentModel->find(session()->get('student_id'));
        $documentModel = new DocumentModel();
        $documents = [];
        if ($student) {
            $documents = $documentModel->getDocumentsByStudent($student['id']);
        }
        return view('student/dashboard', ['student'=>$student, 'documents' => $documents]);
    }

    public function edit()
    {
        $studentModel = new StudentModel();
        $student = $studentModel->find(session()->get('student_id'));
        return view('student/edit_profile', ['student'=>$student]);
    }

    public function update()
    {
        $studentModel = new StudentModel();
        $studentModel->update(session()->get('student_id'), [
            'first_name'=>$this->request->getPost('first_name'),
            'last_name'=>$this->request->getPost('last_name'),
            'email'=>$this->request->getPost('email')
        ]);
        return redirect()->to('/student/dashboard')->with('success','Profile updated');
    }

    public function uploadDocument()
    {
        $studentId = session()->get('student_id');
        if (!$studentId) {
            return redirect()->to('/student/login');
        }

        $file = $this->request->getFile('document_file');
        $docType = $this->request->getPost('document_type');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Please select a valid file.');
        }

        $newName = $file->getRandomName();
        $uploadPath = WRITEPATH . 'uploads/documents';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $file->move($uploadPath, $newName);

        $documentModel = new DocumentModel();
        $documentModel->insert([
            'student_id' => $studentId,
            'document_type' => $docType,
            'file_path' => 'writable/uploads/documents/' . $newName,
            'status' => 'pending',
            'uploaded_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/student/dashboard')->with('success', 'Document uploaded successfully.');
    }

    public function viewDocument($documentId)
    {
        $documentModel = new DocumentModel();
        $doc = $documentModel->find((int)$documentId);
        if (!$doc) {
            throw PageNotFoundException::forPageNotFound();
        }
        $fullPath = ROOTPATH . $doc['file_path'];
        if (!is_file($fullPath)) {
            throw PageNotFoundException::forPageNotFound();
        }
        return $this->response->download($fullPath, null);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/student/login')->with('message', 'Logged out successfully');
    }
}
