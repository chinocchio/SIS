<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');

// $routes->get('/', 'AuthController::landing');

// Auth
// $routes->get('/login', 'AuthController::login');
// $routes->post('/login', 'AuthController::attemptLogin');
// $routes->get('/logout', 'AuthController::logout');

// Admission
$routes->get('/', 'AdmissionController::index');  // Landing page
$routes->get('/admission/enroll', 'AdmissionController::showForm');
$routes->post('/admission/submit', 'AdmissionController::submit');

// Student 
$routes->get('/student/login', 'StudentAuthController::loginForm');
$routes->post('/student/login', 'StudentAuthController::login');
$routes->get('/student/dashboard', 'StudentController::dashboard', ['filter' => 'studentauth']);
$routes->get('/student/profile/edit', 'StudentController::edit');
$routes->post('/student/profile/update', 'StudentController::update');
$routes->get('/student/logout', 'StudentController::logout');
