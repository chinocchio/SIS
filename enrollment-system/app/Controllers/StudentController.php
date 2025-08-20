<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\StudentModel;

class StudentController extends BaseController
{
    public function dashboard()
    {
        $studentModel = new StudentModel();
        $student = $studentModel->find(session()->get('student_id'));
        return view('student/dashboard', ['student'=>$student]);
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

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/student/login')->with('message', 'Logged out successfully');
    }
}
