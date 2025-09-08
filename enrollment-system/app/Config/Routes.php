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
$routes->get('/student/login', 'StudentController::login');
$routes->post('/student/login', 'AuthController::authenticate');
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
$routes->get('/admin', 'AdminController::index', ['filter' => 'adminauth']);
$routes->get('/admin/dashboard', 'AdminController::index', ['filter' => 'adminauth']);
$routes->get('/admin/create-school-year', 'AdminController::createSchoolYear', ['filter' => 'adminauth']);
$routes->post('/admin/create-school-year', 'AdminController::createSchoolYear', ['filter' => 'adminauth']);
           $routes->get('/admin/activate-school-year/(:num)', 'AdminController::activateSchoolYear/$1', ['filter' => 'adminauth']);
           $routes->get('/admin/deactivate-school-year/(:num)', 'AdminController::deactivateSchoolYear/$1', ['filter' => 'adminauth']);
           $routes->get('/admin/delete-school-year/(:num)', 'AdminController::deleteSchoolYear/$1', ['filter' => 'adminauth']);
$routes->get('/admin/create-admission-timeframe', 'AdminController::createAdmissionTimeframe', ['filter' => 'adminauth']);
$routes->post('/admin/create-admission-timeframe', 'AdminController::createAdmissionTimeframe', ['filter' => 'adminauth']);
$routes->get('/admin/edit-admission-timeframe/(:num)', 'AdminController::editAdmissionTimeframe/$1', ['filter' => 'adminauth']);
$routes->post('/admin/edit-admission-timeframe/(:num)', 'AdminController::editAdmissionTimeframe/$1', ['filter' => 'adminauth']);
$routes->get('/admin/delete-admission-timeframe/(:num)', 'AdminController::deleteAdmissionTimeframe/$1', ['filter' => 'adminauth']);
$routes->get('/admin/promote-students', 'AdminController::promoteStudents', ['filter' => 'adminauth']);
$routes->get('/admin/strands', 'AdminController::manageStrands', ['filter' => 'adminauth']);
$routes->post('/admin/addStrand', 'AdminController::addStrand', ['filter' => 'adminauth']);
$routes->get('/admin/test-add', 'AdminController::addStrand', ['filter' => 'adminauth']); // Test route
$routes->post('/admin/editStrand/(:num)', 'AdminController::editStrand/$1', ['filter' => 'adminauth']);
$routes->get('/admin/deleteStrand/(:num)', 'AdminController::deleteStrand/$1', ['filter' => 'adminauth']);

// Registrar Management Routes
$routes->get('/admin/registrars', 'AdminController::manageRegistrars', ['filter' => 'adminauth']);
$routes->get('/admin/registrars/add', 'AdminController::showAddRegistrarForm', ['filter' => 'adminauth']);
$routes->post('/admin/registrars/add', 'AdminController::createRegistrar', ['filter' => 'adminauth']);
$routes->get('/admin/registrars/view/(:num)', 'AdminController::viewRegistrar/$1', ['filter' => 'adminauth']);
$routes->get('/admin/registrars/edit/(:num)', 'AdminController::editRegistrar/$1', ['filter' => 'adminauth']);
$routes->post('/admin/registrars/edit/(:num)', 'AdminController::editRegistrar/$1', ['filter' => 'adminauth']);
$routes->get('/admin/registrars/delete/(:num)', 'AdminController::deleteRegistrar/$1', ['filter' => 'adminauth']);

// Student Management Routes
$routes->get('/admin/students', 'AdminController::manageStudents', ['filter' => 'adminauth']);
$routes->get('/admin/students/add', 'AdminController::showAddStudentForm', ['filter' => 'adminauth']);
$routes->post('/admin/students/add', 'AdminController::addStudentViaSF9', ['filter' => 'adminauth']);
$routes->post('/admin/students/create', 'AdminController::createStudent', ['filter' => 'adminauth']);
$routes->get('/admin/students/edit/(:num)', 'AdminController::editStudent/$1', ['filter' => 'adminauth']);
$routes->post('/admin/students/edit/(:num)', 'AdminController::updateStudent/$1', ['filter' => 'adminauth']);
$routes->get('/admin/students/delete/(:num)', 'AdminController::deleteStudent/$1', ['filter' => 'adminauth']);
$routes->get('/admin/students/view/(:num)', 'AdminController::viewStudent/$1', ['filter' => 'adminauth']);
$routes->get('/admin/students/approve/(:num)', 'AdminController::approveStudent/$1', ['filter' => 'adminauth']);
$routes->post('/admin/students/approve/(:num)', 'AdminController::approveStudent/$1', ['filter' => 'adminauth']);
$routes->post('/admin/students/save-grade', 'AdminController::saveStudentGrade', ['filter' => 'adminauth']);
$routes->get('/admin/documents/view/(:num)', 'AdminController::viewDocument/$1', ['filter' => 'adminauth']);

// Curriculum Management Routes
$routes->get('/admin/curriculums', 'AdminController::manageCurriculums', ['filter' => 'adminauth']);
$routes->post('/admin/curriculums', 'AdminController::addCurriculum', ['filter' => 'adminauth']);
$routes->post('/admin/curriculums/edit/(:num)', 'AdminController::editCurriculum/$1', ['filter' => 'adminauth']);
$routes->get('/admin/curriculums/delete/(:num)', 'AdminController::deleteCurriculum/$1', ['filter' => 'adminauth']);

// Subject Management Routes
$routes->get('/admin/subjects', 'AdminController::manageSubjects', ['filter' => 'adminauth']);
$routes->get('/admin/subjects/add', 'AdminController::showAddSubjectForm', ['filter' => 'adminauth']);
$routes->post('/admin/subjects', 'AdminController::addSubject', ['filter' => 'adminauth']);
$routes->get('/admin/subjects/edit/(:num)', 'AdminController::editSubject/$1', ['filter' => 'adminauth']);
$routes->post('/admin/subjects/edit/(:num)', 'AdminController::editSubject/$1', ['filter' => 'adminauth']);
$routes->get('/admin/subjects/delete/(:num)', 'AdminController::deleteSubject/$1', ['filter' => 'adminauth']);
$routes->post('/admin/subjects/get-by-curriculum', 'AdminController::getSubjectsByCurriculum', ['filter' => 'adminauth']);

// Section Management Routes
$routes->get('/admin/sections', 'AdminController::manageSections', ['filter' => 'adminauth']);
$routes->get('/admin/sections/add', 'AdminController::showAddSectionForm', ['filter' => 'adminauth']);
$routes->post('/admin/sections/add', 'AdminController::createSection', ['filter' => 'adminauth']);
$routes->get('/admin/sections/edit/(:num)', 'AdminController::editSection/$1', ['filter' => 'adminauth']);
$routes->post('/admin/sections/edit/(:num)', 'AdminController::editSection/$1', ['filter' => 'adminauth']);
$routes->get('/admin/sections/delete/(:num)', 'AdminController::deleteSection/$1', ['filter' => 'adminauth']);
$routes->get('/admin/sections/view/(:num)', 'AdminController::viewSection/$1', ['filter' => 'adminauth']);
$routes->post('/admin/students/assign-section', 'AdminController::assignStudentToSection', ['filter' => 'adminauth']);
$routes->get('/admin/students/remove-section/(:num)', 'AdminController::removeStudentFromSection/$1', ['filter' => 'adminauth']);

// Track Management Routes (integrated with strands)
$routes->post('/admin/strands/add-track', 'AdminController::addTrackFromStrands', ['filter' => 'adminauth']);
$routes->post('/admin/strands/edit-track/(:num)', 'AdminController::editTrackFromStrands/$1', ['filter' => 'adminauth']);
$routes->get('/admin/strands/delete-track/(:num)', 'AdminController::deleteTrackFromStrands/$1', ['filter' => 'adminauth']);

$routes->get('/admin/users', 'AdminController::manageUsers', ['filter' => 'adminauth']);
$routes->post('/admin/users', 'AdminController::manageUsers', ['filter' => 'adminauth']);

// Teacher Management Routes
$routes->get('/admin/teachers', 'AdminController::manageTeachers', ['filter' => 'adminauth']);
$routes->get('/admin/teachers/assign', 'AdminController::assignTeachers', ['filter' => 'adminauth']);
$routes->get('/admin/teachers/add', 'AdminController::showAddTeacherForm', ['filter' => 'adminauth']);
$routes->post('/admin/teachers/add', 'AdminController::createTeacher', ['filter' => 'adminauth']);
$routes->get('/admin/teachers/view/(:num)', 'AdminController::viewTeacher/$1', ['filter' => 'adminauth']);
$routes->get('/admin/teachers/edit/(:num)', 'AdminController::showEditTeacherForm/$1', ['filter' => 'adminauth']);
$routes->post('/admin/teachers/edit/(:num)', 'AdminController::updateTeacher/$1', ['filter' => 'adminauth']);
$routes->get('/admin/teachers/delete/(:num)', 'AdminController::deleteTeacher/$1', ['filter' => 'adminauth']);
$routes->post('/admin/teachers/assign-subject', 'AdminController::assignSubjectToTeacher', ['filter' => 'adminauth']);
$routes->get('/admin/teachers/remove-assignment/(:num)', 'AdminController::removeSubjectAssignment/$1', ['filter' => 'adminauth']);
$routes->post('/admin/teachers/get-subjects-by-grade', 'AdminController::getSubjectsByGradeLevel', ['filter' => 'adminauth']);

// Registrar
$routes->get('/registrar', 'RegistrarController::index', ['filter' => 'registrarauth']);
$routes->get('/registrar/dashboard', 'RegistrarController::manageStudents', ['filter' => 'registrarauth']);
$routes->get('/registrar/enrollments/(:any)', 'RegistrarController::viewEnrollments/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/student/(:num)', 'RegistrarController::viewStudent/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/document/approve/(:num)', 'RegistrarController::approveDocument/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/document/reject/(:num)', 'RegistrarController::rejectDocument/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/document/view/(:num)', 'RegistrarController::viewDocument/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/document/download/(:num)', 'RegistrarController::downloadDocument/$1', ['filter' => 'registrarauth']);
$routes->post('/registrar/approve/(:num)', 'RegistrarController::approveEnrollment/$1', ['filter' => 'registrarauth']);
$routes->post('/registrar/reject/(:num)', 'RegistrarController::rejectEnrollment/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/search', 'RegistrarController::searchStudents', ['filter' => 'registrarauth']);
$routes->get('/registrar/report', 'RegistrarController::generateReport', ['filter' => 'registrarauth']);

// Registrar Student Management Routes
$routes->get('/registrar/students', 'RegistrarController::manageStudents', ['filter' => 'registrarauth']);
$routes->get('/registrar/students/add', 'RegistrarController::showAddStudentForm', ['filter' => 'registrarauth']);
$routes->post('/registrar/students/add', 'RegistrarController::createStudent', ['filter' => 'registrarauth']);
$routes->get('/registrar/students/view/(:num)', 'RegistrarController::viewStudent/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/students/edit/(:num)', 'RegistrarController::showEditStudentForm/$1', ['filter' => 'registrarauth']);
$routes->post('/registrar/students/edit/(:num)', 'RegistrarController::updateStudent/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/students/delete/(:num)', 'RegistrarController::deleteStudent/$1', ['filter' => 'registrarauth']);
$routes->get('/registrar/students/approve/(:num)', 'RegistrarController::approveStudent/$1', ['filter' => 'registrarauth']);
$routes->post('/registrar/students/reject/(:num)', 'RegistrarController::rejectStudent/$1', ['filter' => 'registrarauth']);
$routes->post('/registrar/students/assign-section', 'RegistrarController::assignStudentToSection', ['filter' => 'registrarauth']);
$routes->get('/registrar/students/remove-section/(:num)', 'RegistrarController::removeStudentFromSection/$1', ['filter' => 'registrarauth']);

// Teacher
$routes->get('/teacher', 'TeacherController::index', ['filter' => 'teacherauth']);
$routes->get('/teacher/dashboard', 'TeacherController::index', ['filter' => 'teacherauth']);
$routes->get('/teacher/students/(:num)', 'TeacherController::viewStudents/$1', ['filter' => 'teacherauth']);
$routes->get('/teacher/grades/(:num)', 'TeacherController::inputGrades/$1', ['filter' => 'teacherauth']);
$routes->post('/teacher/save-grades', 'TeacherController::saveGrades', ['filter' => 'teacherauth']);
$routes->get('/teacher/grades', 'TeacherController::gradeManagement', ['filter' => 'teacherauth']);
$routes->get('/teacher/reports', 'TeacherController::reports', ['filter' => 'teacherauth']);
$routes->get('/teacher/student/(:num)/grades/(:num)', 'TeacherController::viewGrades/$1/$2', ['filter' => 'teacherauth']);
$routes->get('/teacher/student/(:num)/report-card/(:num)', 'TeacherController::generateReportCard/$1/$2', ['filter' => 'teacherauth']);

// Student
$routes->get('/student/dashboard', 'StudentController::index', ['filter' => 'studentauth']);
$routes->post('/student/submit-document', 'StudentController::submitDocument', ['filter' => 'studentauth']);
$routes->get('/student/change-password', 'StudentController::changePassword', ['filter' => 'studentauth']);
$routes->post('/student/change-password', 'StudentController::changePassword', ['filter' => 'studentauth']);
$routes->get('/student/document/view/(:num)', 'StudentController::viewDocument/$1', ['filter' => 'studentauth']);
$routes->get('/student/document/download/(:num)', 'StudentController::downloadDocument/$1', ['filter' => 'studentauth']);

// API for face recognition app
$routes->post('/api/attendance/record', 'ApiController::recordAttendance');
$routes->get('/api/session/active', 'ApiController::getActiveSession');
