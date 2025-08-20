<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\StudentModel;

class AdmissionController extends BaseController
{
    public function index()
    {
        return view('landing');
    }
    
    public function showForm()
    {
        return view('admission/form');
    }

    public function submit()
    {
        $studentModel = new StudentModel();

        $data = [
            'first_name'   => $this->request->getPost('first_name'),
            'last_name'    => $this->request->getPost('last_name'),
            'email'        => $this->request->getPost('email'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'grade_level'  => $this->request->getPost('grade_level'),
            'admission_type'=> $this->request->getPost('admission_type'), // regular, transferee, re-enroll
            'strand_id'    => $this->request->getPost('strand_id'),
            'status'       => 'pending'
        ];

        $studentModel->insert($data);

        return redirect()->to('/student/login')->with('success', 'Your admission has been submitted. Please login.');
    }
}
