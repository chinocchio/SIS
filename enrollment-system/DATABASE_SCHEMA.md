# Database Schema Documentation

## 📊 Overview

The School Information System uses MySQL/MariaDB with the following main tables:

## 🗂️ Core Tables

### `users`
- **Purpose**: Admin and Registrar accounts
- **Key Fields**: `username`, `password`, `role`, `first_name`, `last_name`, `email`

### `teachers`
- **Purpose**: Teacher accounts and information
- **Key Fields**: `username`, `password`, `first_name`, `last_name`, `email`, `specialization`

### `students`
- **Purpose**: Student information and enrollment data
- **Key Fields**: `lrn`, `full_name`, `email`, `password`, `status`, `section_id`, `curriculum_id`, `strand_id`, `face_encoding`

### `school_years`
- **Purpose**: Academic year management
- **Key Fields**: `name`, `start_date`, `end_date`, `is_active`

### `curriculums`
- **Purpose**: Junior High School curriculum (JHS)
- **Key Fields**: `name`, `description`, `is_active`

### `strands`
- **Purpose**: Senior High School strands (SHS)
- **Key Fields**: `name`, `description`, `is_active`

### `sections`
- **Purpose**: Class sections for students
- **Key Fields**: `name`, `grade_level`, `curriculum_id`, `strand_id`, `school_year_id`

### `subjects`
- **Purpose**: Subject/course information
- **Key Fields**: `name`, `code`, `grade_level`, `curriculum_id`, `strand_id`, `quarter`, `semester`

### `teacher_subject_assignments`
- **Purpose**: Links teachers to subjects and sections
- **Key Fields**: `teacher_id`, `subject_id`, `section_id`, `school_year_id`, `is_active`

### `student_grades`
- **Purpose**: Student academic grades
- **Key Fields**: `student_id`, `subject_id`, `section_id`, `quarter`, `semester`, `grade`, `recorded_by`

### `documents`
- **Purpose**: Student document submissions
- **Key Fields**: `student_id`, `document_type`, `file_path`, `uploaded_at`

### `attendance_records`
- **Purpose**: Face recognition attendance tracking
- **Key Fields**: `student_id`, `subject_id`, `recorded_at`

## 🔗 Key Relationships

```
school_years (1) ──→ (many) sections
curriculums (1) ──→ (many) sections
strands (1) ──→ (many) sections
sections (1) ──→ (many) students
teachers (1) ──→ (many) teacher_subject_assignments
subjects (1) ──→ (many) teacher_subject_assignments
students (1) ──→ (many) student_grades
students (1) ──→ (many) documents
students (1) ──→ (many) attendance_records
```

## 📋 Sample Data Structure

### Student Record Example
```sql
INSERT INTO students (
    lrn, full_name, email, password, status, 
    section_id, curriculum_id, grade_level,
    created_at, updated_at
) VALUES (
    '123456789012', 'John Doe', 'john@student.edu', 
    '$2y$10$...', 'approved', 1, 1, 7, NOW(), NOW()
);
```

### Teacher Assignment Example
```sql
INSERT INTO teacher_subject_assignments (
    teacher_id, subject_id, section_id, school_year_id, is_active
) VALUES (1, 5, 2, 1, 1);
```

## 🔧 Migration Commands

```bash
# Check migration status
php spark migrate:status

# Run all migrations
php spark migrate

# Rollback last migration
php spark migrate:rollback

# Refresh all migrations
php spark migrate:refresh
```

## 📊 Indexes and Performance

### Key Indexes
- `students.lrn` (UNIQUE)
- `users.username` (UNIQUE)
- `teachers.username` (UNIQUE)
- `attendance_records.student_id, subject_id, recorded_at`
- `student_grades.student_id, subject_id`

### Query Optimization Tips
- Use JOINs instead of multiple queries
- Index frequently queried columns
- Use LIMIT for pagination
- Cache frequently accessed data

## 🛡️ Security Considerations

### Password Storage
- All passwords use PHP's `password_hash()` with bcrypt
- Default cost factor: 10

### Data Validation
- Server-side validation in Models
- CSRF protection on forms
- SQL injection prevention via Query Builder

### File Upload Security
- File type validation
- Upload size limits
- Secure file storage outside web root

## 📈 Backup Strategy

### Daily Backup Script
```bash
#!/bin/bash
mysqldump -u username -p sis_enrollment > backup_$(date +%Y%m%d).sql
```

### Restore Database
```bash
mysql -u username -p sis_enrollment < backup_20240101.sql
```

---

**Note**: Always backup your database before running migrations in production! 🚨
