<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\StudentModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function landing()
    {
        return view('landing');
    }

    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Check student first
        $studentModel = new StudentModel();
        $student = $studentModel->where('email', $email)->first();

        if ($student && password_verify($password, $student['password'])) {
            session()->set([
                'id'   => $student['id'],
                'role' => 'student',
                'logged_in' => true
            ]);
            return redirect()->to('/student/dashboard');
        }

        // Check admin/registrar
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'id'   => $user['id'],
                'role' => $user['role'],
                'logged_in' => true
            ]);
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
