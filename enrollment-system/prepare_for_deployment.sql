-- Database Preparation Script for DigitalOcean Deployment
-- Run this on your local MySQL database before deployment

-- Create a clean backup of your database
-- This script will help you prepare your database for deployment

-- 1. Create a backup user (optional, for backup purposes)
-- CREATE USER 'backup_user'@'localhost' IDENTIFIED BY 'backup_password';
-- GRANT SELECT, LOCK TABLES ON enrollment_db.* TO 'backup_user'@'localhost';

-- 2. Ensure all tables have proper character sets
ALTER DATABASE enrollment_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 3. Check and fix any data issues before deployment
-- Update any localhost URLs to production URLs
UPDATE students SET email = REPLACE(email, 'localhost', 'yourdomain.com') WHERE email LIKE '%localhost%';

-- 4. Ensure all face encodings are properly formatted
-- Check for any corrupted face encodings
SELECT id, full_name, LENGTH(face_encoding) as encoding_length 
FROM students 
WHERE face_encoding IS NOT NULL 
AND LENGTH(face_encoding) < 100;

-- 5. Clean up any test data if needed
-- DELETE FROM attendance_records WHERE recorded_at < '2025-01-01';
-- DELETE FROM student_grades WHERE created_at < '2025-01-01';

-- 6. Ensure all foreign key constraints are properly set
-- Check for orphaned records
SELECT COUNT(*) as orphaned_students 
FROM students s 
LEFT JOIN sections sec ON s.section_id = sec.id 
WHERE s.section_id IS NOT NULL AND sec.id IS NULL;

SELECT COUNT(*) as orphaned_assignments 
FROM teacher_subject_assignments tsa 
LEFT JOIN teachers t ON tsa.teacher_id = t.id 
WHERE t.id IS NULL;

-- 7. Optimize tables for production
OPTIMIZE TABLE students;
OPTIMIZE TABLE teachers;
OPTIMIZE TABLE subjects;
OPTIMIZE TABLE sections;
OPTIMIZE TABLE teacher_subject_assignments;
OPTIMIZE TABLE student_grades;
OPTIMIZE TABLE attendance_records;
OPTIMIZE TABLE documents;

-- 8. Create indexes for better performance (if not already exist)
-- CREATE INDEX idx_students_lrn ON students(lrn);
-- CREATE INDEX idx_students_status ON students(status);
-- CREATE INDEX idx_attendance_student_subject ON attendance_records(student_id, subject_id);
-- CREATE INDEX idx_attendance_date ON attendance_records(recorded_at);

-- 9. Set proper SQL modes for production
SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';

-- 10. Final verification queries
SELECT 'Database preparation completed successfully!' as status;

-- Show table counts for verification
SELECT 
    'students' as table_name, COUNT(*) as record_count FROM students
UNION ALL
SELECT 'teachers', COUNT(*) FROM teachers
UNION ALL
SELECT 'subjects', COUNT(*) FROM subjects
UNION ALL
SELECT 'sections', COUNT(*) FROM sections
UNION ALL
SELECT 'teacher_subject_assignments', COUNT(*) FROM teacher_subject_assignments
UNION ALL
SELECT 'student_grades', COUNT(*) FROM student_grades
UNION ALL
SELECT 'attendance_records', COUNT(*) FROM attendance_records
UNION ALL
SELECT 'documents', COUNT(*) FROM documents;

-- Show students with face encodings
SELECT COUNT(*) as students_with_faces FROM students WHERE face_encoding IS NOT NULL;

-- Show active school year
SELECT * FROM school_years WHERE is_active = 1;

