<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RegistrarAuth implements FilterInterface
{
    /**
     * Runs before the controller method.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Require logged-in registrar
        if (!$session->get('is_logged_in') || $session->get('role') !== 'registrar') {
            return redirect()->to('/auth/login')->with('error', 'Please login as registrar to access this page.');
        }
    }

    /**
     * Runs after the controller method.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}


