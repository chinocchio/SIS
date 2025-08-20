# Student Information System (SIS)

A comprehensive Student Information System built with CodeIgniter 4 that manages student admissions, grade progression, and academic records.

## Features

### 🎓 Student Admission Management
- **Smart Admission Type Detection**: Automatically determines if a student is regular, transferee, re-enrolling, or promoted
- **Grade Level Validation**: Ensures proper grade progression and strand selection
- **Admission Timeframes**: Configurable periods when students can apply
- **Document Requirements**: Automatic detection of document requirements based on student type

### 🏫 School Year Management
- **Academic Year Control**: Create and manage multiple school years
- **Automatic Grade Progression**: Students automatically advance to next grade level
- **Grade Validation**: Students with failing grades (<75) cannot progress
- **Year Transition**: Seamless transition between academic years

### 📚 Strand/Track Management
- **Dynamic Strand Selection**: Dropdown-based strand selection for SHS students
- **Configurable Options**: Admin can add, edit, and manage available strands
- **Grade Level Integration**: Strands only available for Grade 11-12 (SHS)

### 👨‍🏫 Teacher Grade Management
- **Grade Input System**: Teachers can input and update student grades
- **Subject Assignment**: Track which teachers teach which subjects
- **Grade Validation**: Ensures grades are within valid range (0-100)
- **Report Card Generation**: Automatic calculation of averages and pass/fail status

### 📊 Student Sectioning
- **Dynamic Sectioning**: Sections with 35-40 student capacity
- **Grade-Based Ranking**: SHS sections determined by academic performance
- **Flexible Assignment**: Students can be assigned to different sections per school year

### 🔐 User Management
- **Student Authentication**: Secure login system for students
- **Admin Panel**: Comprehensive administration interface
- **Teacher Access**: Dedicated teacher dashboard for grade management

## Business Rules

### Admission Types
1. **Regular**: New students starting at Grade 7
2. **Transferee**: Students entering at Grade 8+ without previous enrollment
3. **Re-enroll**: Students repeating the same grade level
4. **Promoted**: Students advancing to next grade level

### Grade Progression Rules
- Students must have grades ≥75 in all subjects to progress
- Automatic promotion at end of school year
- JHS graduates (Grade 10) must re-enroll for SHS (Grade 11)
- No documents required for JHS to SHS transition

### Sectioning Rules
- **JHS (Grade 7-10)**: Fixed subjects, grade-based ranking
- **SHS (Grade 11-12)**: Strand-specific subjects, performance-based sections
- Section capacity: 35-40 students
- SHS sections: 11-A, 11-B, etc. (performance-based)

## Database Structure

### Core Tables
- `students` - Student information and admission details
- `school_years` - Academic year management
- `sections` - Class sections with capacity limits
- `subjects` - Course offerings by grade level and strand
- `strands` - SHS track options (STEM, ABM, HUMSS, TVL)
- `student_grades` - Academic performance records
- `student_sections` - Section assignments and rankings
- `admission_timeframes` - Application period controls

### Supporting Tables
- `teachers` - Faculty information
- `teacher_subject_assignments` - Subject teaching assignments
- `users` - System user accounts

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Composer

### Setup Steps
1. Clone the repository
2. Install dependencies: `composer install`
3. Configure database in `app/Config/Database.php`
4. Run migrations: `php spark migrate`
5. Seed initial data: `php spark db:seed`
6. Configure web server to point to `public/` directory

### Database Seeding
```bash
# Seed default data
php spark db:seed StrandSeeder
php spark db:seed SchoolYearSeeder
php spark db:seed AdmissionTimeframeSeeder
```

## Usage

### Admin Access
- **URL**: `/admin`
- **Features**: School year management, admission timeframes, student promotions

### Student Admission
- **URL**: `/admission/enroll`
- **Features**: Online application form with automatic validation

### Teacher Dashboard
- **URL**: `/teacher`
- **Features**: Grade input, student management, report generation

## Configuration

### Admission Timeframes
- Set start and end dates for application periods
- Outside timeframe: admission form is closed
- Multiple timeframes per school year supported

### School Year Management
- Create new academic years
- Activate/deactivate school years
- Automatic student promotion at year end

### Strand Configuration
- Add/edit available SHS strands
- Set descriptions and active status
- Dynamic form integration

## Security Features

- Password hashing for all user accounts
- Session-based authentication
- Input validation and sanitization
- CSRF protection
- Role-based access control

## API Endpoints

### Admin
- `GET /admin` - Dashboard
- `POST /admin/create-school-year` - Create school year
- `GET /admin/promote-students` - Promote students
- `POST /admin/strands` - Manage strands

### Admission
- `GET /admission/enroll` - Show form
- `POST /admission/submit` - Submit application

### Teacher
- `GET /teacher` - Teacher dashboard
- `POST /teacher/input-grades` - Input grades
- `GET /teacher/student/{id}/grades/{year}` - View grades

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support and questions, please contact the development team or create an issue in the repository.

## Changelog

### Version 1.0.0
- Initial release with core admission system
- School year management
- Grade progression system
- Teacher grade management
- Admin dashboard
- Dynamic strand selection
- Admission timeframes
- Student sectioning
