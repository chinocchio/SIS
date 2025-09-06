<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TeacherModel;
use App\Models\StudentModel;

class AuthController extends BaseController
{
    public function index()
    {
        return view('landing');
    }
    
    public function login()
    {
        return view('auth/login');
    }
    
    public function authenticate()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // First check users table (for admin, registrar)
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)
                         ->where('is_active', 1)
                         ->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
            
            // Set session data
            $session = session();
            $session->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'is_logged_in' => true
            ]);
            
            // Redirect based on role
            switch ($user['role']) {
                case 'admin':
                    return redirect()->to('/admin/dashboard');
                case 'registrar':
                    return redirect()->to('/registrar/dashboard');
                default:
                    return redirect()->to('/auth/login')->with('error', 'Invalid user role.');
            }
        }
        
        // Check teachers table
        $teacherModel = new TeacherModel();
        $teacher = $teacherModel->where('username', $username)
                               ->where('is_active', 1)
                               ->first();
        
        if ($teacher && password_verify($password, $teacher['password'])) {
            // Set session data for teacher
            $session = session();
            $session->set([
                'user_id' => $teacher['id'],
                'username' => $teacher['username'],
                'role' => 'teacher',
                'first_name' => $teacher['first_name'],
                'last_name' => $teacher['last_name'],
                'email' => $teacher['email'],
                'is_logged_in' => true
            ]);
            
            return redirect()->to('/teacher/dashboard');
        }
        
        // Check students table using LRN
        $studentModel = new StudentModel();
        $student = $studentModel->where('lrn', $username)->first();
        
        if ($student) {
            // Check if student is approved
            if ($student['status'] !== 'approved') {
                return redirect()->back()->with('error', 'Your account is not yet approved. Please contact the registrar.');
            }
            
            // Verify password
            if (password_verify($password, $student['password'])) {
                // Set session data for student
                $session = session();
                $session->set([
                    'user_id' => $student['id'],
                    'username' => $student['lrn'],
                    'role' => 'student',
                    'first_name' => $student['first_name'],
                    'last_name' => $student['last_name'],
                    'email' => $student['email'],
                    'lrn' => $student['lrn'],
                    'status' => $student['status'],
                    'is_logged_in' => true
                ]);
                
                return redirect()->to('/student/dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        }
        
        // If none found, return error
        return redirect()->back()->with('error', 'Invalid username/LRN or password.');
    }
    
    public function logout()
    {
        try {
            $session = session();
            
            // Regenerate session ID for security (do this before destroying)
            session_regenerate_id(true);
            
            // Clear all session data
            $session->destroy();
            
            // Clear any cookies that might be set
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            
            // Additional cleanup - unset all session variables
            $_SESSION = array();
            
            return redirect()->to('/auth/login')->with('success', 'Successfully logged out.');
        } catch (\Exception $e) {
            // If there's an error, still try to clear the session
            session_destroy();
            return redirect()->to('/auth/login')->with('success', 'Successfully logged out.');
        }
    }
    
    public function changePassword()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        if ($this->request->getMethod() === 'post') {
            $currentPassword = $this->request->getPost('current_password');
            $newPassword = $this->request->getPost('new_password');
            $confirmPassword = $this->request->getPost('confirm_password');
            
            if ($newPassword !== $confirmPassword) {
                return redirect()->back()->with('error', 'New passwords do not match.');
            }
            
            $userModel = new UserModel();
            $user = $userModel->find(session()->get('user_id'));
            
            if (!password_verify($currentPassword, $user['password'])) {
                return redirect()->back()->with('error', 'Current password is incorrect.');
            }
            
            $userModel->update($user['id'], [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT)
            ]);
            
            return redirect()->back()->with('success', 'Password changed successfully.');
        }
        
        return view('auth/change_password');
    }
}
