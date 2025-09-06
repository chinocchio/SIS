<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class StudentAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/auth/login');
        }
        
        // Check if user has student role
        if ($session->get('role') !== 'student') {
            return redirect()->to('/auth/login')->with('error', 'Access denied. Student role required.');
        }
        
        // Check if student is approved
        if ($session->get('status') === 'rejected') {
            return redirect()->to('/auth/login')->with('error', 'Your account has been rejected. Please contact the registrar.');
        }
        
        return $request;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}