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

// Home
$routes->get('/', 'Home::index');  // Landing page

// Student 
$routes->get('/student/login', 'StudentAuthController::loginForm');
$routes->post('/student/login', 'StudentAuthController::login');
$routes->get('/student/dashboard', 'StudentController::dashboard', ['filter' => 'studentauth']);
$routes->get('/student/profile/edit', 'StudentController::edit');
$routes->post('/student/profile/update', 'StudentController::update');
$routes->post('/student/upload-document', 'StudentController::uploadDocument');
$routes->get('/student/document/(:num)', 'StudentController::viewDocument/$1');
$routes->get('/student/logout', 'StudentController::logout');

// Auth
$routes->get('/auth/login', 'AuthController::login');
$routes->post('/auth/authenticate', 'AuthController::authenticate');
$routes->get('/auth/logout', 'AuthController::logout');
$routes->get('/auth/change-password', 'AuthController::changePassword');
$routes->post('/auth/change-password', 'AuthController::changePassword');

// Admin
$routes->get('/admin', 'AdminController::index');
$routes->get('/admin/dashboard', 'AdminController::index');
$routes->get('/admin/create-school-year', 'AdminController::createSchoolYear');
$routes->post('/admin/create-school-year', 'AdminController::createSchoolYear');
           $routes->get('/admin/activate-school-year/(:num)', 'AdminController::activateSchoolYear/$1');
           $routes->get('/admin/deactivate-school-year/(:num)', 'AdminController::deactivateSchoolYear/$1');
           $routes->get('/admin/delete-school-year/(:num)', 'AdminController::deleteSchoolYear/$1');
$routes->get('/admin/create-admission-timeframe', 'AdminController::createAdmissionTimeframe');
$routes->post('/admin/create-admission-timeframe', 'AdminController::createAdmissionTimeframe');
$routes->get('/admin/edit-admission-timeframe/(:num)', 'AdminController::editAdmissionTimeframe/$1');
$routes->post('/admin/edit-admission-timeframe/(:num)', 'AdminController::editAdmissionTimeframe/$1');
$routes->get('/admin/delete-admission-timeframe/(:num)', 'AdminController::deleteAdmissionTimeframe/$1');
$routes->get('/admin/promote-students', 'AdminController::promoteStudents');
$routes->get('/admin/strands', 'AdminController::manageStrands');
$routes->post('/admin/addStrand', 'AdminController::addStrand');
$routes->get('/admin/test-add', 'AdminController::addStrand'); // Test route
$routes->post('/admin/editStrand/(:num)', 'AdminController::editStrand/$1');
$routes->get('/admin/deleteStrand/(:num)', 'AdminController::deleteStrand/$1');

// Student Management Routes
$routes->get('/admin/students', 'AdminController::manageStudents');
$routes->get('/admin/students/add', 'AdminController::showAddStudentForm');
$routes->post('/admin/students/add', 'AdminController::addStudentViaSF9');
$routes->post('/admin/students/create', 'AdminController::createStudent');
$routes->get('/admin/students/edit/(:num)', 'AdminController::editStudent/$1');
$routes->post('/admin/students/edit/(:num)', 'AdminController::updateStudent/$1');
$routes->get('/admin/students/delete/(:num)', 'AdminController::deleteStudent/$1');
$routes->get('/admin/students/view/(:num)', 'AdminController::viewStudent/$1');
$routes->post('/admin/students/save-grade', 'AdminController::saveStudentGrade');

// Curriculum Management Routes
$routes->get('/admin/curriculums', 'AdminController::manageCurriculums');
$routes->post('/admin/curriculums', 'AdminController::addCurriculum');
$routes->post('/admin/curriculums/edit/(:num)', 'AdminController::editCurriculum/$1');
$routes->get('/admin/curriculums/delete/(:num)', 'AdminController::deleteCurriculum/$1');

// Subject Management Routes
$routes->get('/admin/subjects', 'AdminController::manageSubjects');
$routes->get('/admin/subjects/add', 'AdminController::showAddSubjectForm');
$routes->post('/admin/subjects', 'AdminController::addSubject');
$routes->get('/admin/subjects/edit/(:num)', 'AdminController::editSubject/$1');
$routes->post('/admin/subjects/edit/(:num)', 'AdminController::editSubject/$1');
$routes->get('/admin/subjects/delete/(:num)', 'AdminController::deleteSubject/$1');
$routes->post('/admin/subjects/get-by-curriculum', 'AdminController::getSubjectsByCurriculum');

// Track Management Routes (integrated with strands)
$routes->post('/admin/strands/add-track', 'AdminController::addTrackFromStrands');
$routes->post('/admin/strands/edit-track/(:num)', 'AdminController::editTrackFromStrands/$1');
$routes->get('/admin/strands/delete-track/(:num)', 'AdminController::deleteTrackFromStrands/$1');

$routes->get('/admin/users', 'AdminController::manageUsers');
$routes->post('/admin/users', 'AdminController::manageUsers');

// Registrar
$routes->get('/registrar', 'RegistrarController::index');
$routes->get('/registrar/dashboard', 'RegistrarController::dashboard');
$routes->get('/registrar/enrollments/(:any)', 'RegistrarController::viewEnrollments/$1');
$routes->get('/registrar/student/(:num)', 'RegistrarController::viewStudent/$1');
$routes->get('/registrar/document/approve/(:num)', 'RegistrarController::approveDocument/$1');
$routes->get('/registrar/document/reject/(:num)', 'RegistrarController::rejectDocument/$1');
$routes->get('/registrar/document/view/(:num)', 'RegistrarController::viewDocument/$1');
$routes->post('/registrar/approve/(:num)', 'RegistrarController::approveEnrollment/$1');
$routes->post('/registrar/reject/(:num)', 'RegistrarController::rejectEnrollment/$1');
$routes->get('/registrar/search', 'RegistrarController::searchStudents');
$routes->get('/registrar/report', 'RegistrarController::generateReport');

// Teacher
$routes->get('/teacher', 'TeacherController::index');
$routes->get('/teacher/dashboard', 'TeacherController::index');
$routes->get('/teacher/section/(:num)/students', 'TeacherController::viewStudents/$1');
$routes->post('/teacher/input-grades', 'TeacherController::inputGrades');
$routes->get('/teacher/student/(:num)/grades/(:num)', 'TeacherController::viewGrades/$1/$2');
$routes->get('/teacher/student/(:num)/report-card/(:num)', 'TeacherController::generateReportCard/$1/$2');
