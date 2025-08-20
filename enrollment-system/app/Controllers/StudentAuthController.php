<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\StudentModel;

class StudentAuthController extends BaseController
{
    public function loginForm()
    {
        return view('student/login');
    }

    public function login()
    {
        $studentModel = new StudentModel();
        $student = $studentModel->where('email', $this->request->getPost('email'))->first();

        if ($student && password_verify($this->request->getPost('password'), $student['password'])) {
            session()->set('student_id', $student['id']);
            return redirect()->to('/student/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials.');
    }
}
