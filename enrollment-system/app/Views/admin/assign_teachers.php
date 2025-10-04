<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Teachers to Subjects - Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f6fb;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .header h1 {
            color: #333;
            margin: 0;
        }
        
        .btn {
            background: #667eea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
        
        .btn:hover {
            background: #5a6fd8;
        }
        
        .btn-secondary {
            background: #6c757d;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-success {
            background: #28a745;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-danger {
            background: #dc3545;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .filters-section {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #bbdefb;
            margin-bottom: 30px;
        }
        
        .filters-section h3 {
            margin-top: 0;
            color: #1976d2;
        }
        
        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #495057;
        }
        
        select, input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        
        .assignments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .assignments-table th,
        .assignments-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .assignments-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .assignments-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .assignment-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            margin-top: 20px;
        }
        
        .assignment-form h4 {
            margin-top: 0;
            color: #495057;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 15px;
            margin-bottom: 15px;
            align-items: end;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👨‍🏫 Assign Teachers to Subjects</h1>
            <div>
                <a href="/admin/teachers" class="btn btn-secondary">← Back to Teachers</a>
                <a href="/admin/dashboard" class="btn">Dashboard</a>
            </div>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $totalTeachers ?></div>
                <div class="stat-label">Total Teachers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalAssignments ?></div>
                <div class="stat-label">Active Assignments</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalSubjects ?></div>
                <div class="stat-label">Available Subjects</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $totalSections ?></div>
                <div class="stat-label">Available Sections</div>
            </div>
        </div>
        
        <!-- Assignment Form -->
        <div class="assignment-form">
            <h4>➕ Assign Teacher to Subject</h4>
            <?php if ($activeSchoolYear): ?>
                <form method="POST" action="/admin/teachers/assign-subject">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="teacher_id">Teacher *</label>
                            <select name="teacher_id" id="teacher_id" required>
                                <option value="">Select Teacher</option>
                                <?php if (!empty($teachers)): ?>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <option value="<?= $teacher['id'] ?>">
                                            <?= esc($teacher['first_name'] . ' ' . $teacher['last_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="grade_level">Grade Level *</label>
                            <select name="grade_level" id="grade_level" required onchange="filterSubjectsAndSections()">
                                <option value="">Select Grade Level</option>
                                <option value="7">Grade 7</option>
                                <option value="8">Grade 8</option>
                                <option value="9">Grade 9</option>
                                <option value="10">Grade 10</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject_id">Subject *</label>
                            <select name="subject_id" id="subject_id" required disabled>
                                <option value="">Select Grade Level First</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="section_id">Section *</label>
                            <select name="section_id" id="section_id" required disabled>
                                <option value="">Select Grade Level First</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" disabled id="assign-btn">Assign</button>
                        </div>
                    </div>
                    
                    <input type="hidden" name="school_year_id" value="<?= $activeSchoolYear['id'] ?>">
                </form>
                
                <!-- Hidden data for JavaScript -->
                <script>
                    // Store subjects data
                    const subjectsData = <?= json_encode($subjects ?? []) ?>;
                    const sectionsData = <?= json_encode($sections ?? []) ?>;
                    
                    // Debug: Log the data to console
                    console.log('Subjects Data:', subjectsData);
                    console.log('Sections Data:', sectionsData);
                    
                    function filterSubjectsAndSections() {
                        const gradeLevel = document.getElementById('grade_level').value;
                        const subjectSelect = document.getElementById('subject_id');
                        const sectionSelect = document.getElementById('section_id');
                        const assignBtn = document.getElementById('assign-btn');
                        
                        // Clear previous options
                        subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                        sectionSelect.innerHTML = '<option value="">Select Section</option>';
                        
                        if (gradeLevel) {
                            // Filter subjects by grade level (convert both to numbers for comparison)
                            const filteredSubjects = subjectsData.filter(subject => parseInt(subject.grade_level) === parseInt(gradeLevel));
                            
                            // Debug: Log filtering results
                            console.log('Grade Level Selected:', gradeLevel);
                            console.log('Filtered Subjects for Grade ' + gradeLevel + ':', filteredSubjects);
                            
                            filteredSubjects.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                let displayText = subject.name;
                                
                                // Add curriculum name if available, otherwise indicate it's SHS
                                if (subject.curriculum_name) {
                                    displayText += ' (' + subject.curriculum_name + ')';
                                } else {
                                    displayText += ' (SHS Subject)';
                                }
                                
                                option.textContent = displayText;
                                subjectSelect.appendChild(option);
                            });
                            
                            // Filter sections by grade level (convert both to numbers for comparison)
                            const filteredSections = sectionsData.filter(section => parseInt(section.grade_level) === parseInt(gradeLevel));
                            
                            filteredSections.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name + ' (Grade ' + section.grade_level + ')';
                                sectionSelect.appendChild(option);
                            });
                            
                            // Enable selects
                            subjectSelect.disabled = false;
                            sectionSelect.disabled = false;
                        } else {
                            // Disable selects
                            subjectSelect.disabled = true;
                            sectionSelect.disabled = true;
                            assignBtn.disabled = true;
                        }
                    }
                    
                    // Enable assign button when all fields are filled
                    document.getElementById('teacher_id').addEventListener('change', checkForm);
                    document.getElementById('subject_id').addEventListener('change', checkForm);
                    document.getElementById('section_id').addEventListener('change', checkForm);
                    
                    function checkForm() {
                        const teacherId = document.getElementById('teacher_id').value;
                        const gradeLevel = document.getElementById('grade_level').value;
                        const subjectId = document.getElementById('subject_id').value;
                        const sectionId = document.getElementById('section_id').value;
                        const assignBtn = document.getElementById('assign-btn');
                        
                        if (teacherId && gradeLevel && subjectId && sectionId) {
                            assignBtn.disabled = false;
                        } else {
                            assignBtn.disabled = true;
                        }
                    }
                </script>
            <?php else: ?>
                <div class="no-data">
                    <p><strong>⚠️ No Active School Year Found</strong></p>
                    <p>You need to set an active school year before assigning teachers to subjects.</p>
                    <a href="/admin/create-school-year" class="btn btn-warning">Create School Year</a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Current Assignments -->
        <div class="filters-section">
            <h3>📋 Current Teacher Assignments</h3>
            
            <!-- Filter Controls -->
            <div class="filters-grid">
                <div class="form-group">
                    <label for="filter_teacher">Filter by Teacher</label>
                    <select id="filter_teacher" onchange="filterAssignments()">
                        <option value="">All Teachers</option>
                        <?php if (!empty($assignments)): ?>
                            <?php 
                            $uniqueTeachers = [];
                            foreach ($assignments as $assignment) {
                                $teacherName = $assignment['teacher_name'] ?? 'Unknown';
                                if (!in_array($teacherName, $uniqueTeachers)) {
                                    $uniqueTeachers[] = $teacherName;
                                }
                            }
                            foreach ($uniqueTeachers as $teacherName): ?>
                                <option value="<?= esc($teacherName) ?>"><?= esc($teacherName) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_subject">Filter by Subject</label>
                    <select id="filter_subject" onchange="filterAssignments()">
                        <option value="">All Subjects</option>
                        <?php if (!empty($assignments)): ?>
                            <?php 
                            $uniqueSubjects = [];
                            foreach ($assignments as $assignment) {
                                $subjectName = $assignment['subject_name'] ?? 'Unknown';
                                if (!in_array($subjectName, $uniqueSubjects)) {
                                    $uniqueSubjects[] = $subjectName;
                                }
                            }
                            foreach ($uniqueSubjects as $subjectName): ?>
                                <option value="<?= esc($subjectName) ?>"><?= esc($subjectName) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_section">Filter by Section</label>
                    <select id="filter_section" onchange="filterAssignments()">
                        <option value="">All Sections</option>
                        <?php if (!empty($assignments)): ?>
                            <?php 
                            $uniqueSections = [];
                            foreach ($assignments as $assignment) {
                                $sectionName = $assignment['section_name'] ?? 'Unknown';
                                if (!in_array($sectionName, $uniqueSections)) {
                                    $uniqueSections[] = $sectionName;
                                }
                            }
                            foreach ($uniqueSections as $sectionName): ?>
                                <option value="<?= esc($sectionName) ?>"><?= esc($sectionName) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="filter_status">Filter by Status</label>
                    <select id="filter_status" onchange="filterAssignments()">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="search_box">Search</label>
                    <input type="text" id="search_box" placeholder="Search assignments..." onkeyup="filterAssignments()">
                </div>
                
                <div class="form-group">
                    <button type="button" class="btn btn-secondary" onclick="clearFilters()">Clear Filters</button>
                </div>
            </div>
            
            <?php if (!empty($assignments) && is_array($assignments)): ?>
                <table class="assignments-table" id="assignmentsTable">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Section</th>
                            <th>School Year</th>
                            <th>Assigned Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assignments as $assignment): ?>
                            <tr data-teacher="<?= esc($assignment['teacher_name'] ?? 'Unknown') ?>" 
                                data-subject="<?= esc($assignment['subject_name'] ?? 'Unknown') ?>"
                                data-section="<?= esc($assignment['section_name'] ?? 'Unknown') ?>"
                                data-status="<?= $assignment['is_active'] ? 'active' : 'inactive' ?>"
                                data-search-text="<?= esc(strtolower($assignment['teacher_name'] . ' ' . $assignment['subject_name'] . ' ' . $assignment['section_name'] . ' ' . $assignment['school_year'])) ?>">
                                <td>
                                    <strong><?= esc($assignment['teacher_name'] ?? 'Unknown') ?></strong>
                                </td>
                                <td>
                                    <strong><?= esc($assignment['subject_name'] ?? 'Unknown') ?></strong>
                                    <br><small><?= esc($assignment['subject_code'] ?? 'N/A') ?></small>
                                </td>
                                <td>
                                    <?= esc($assignment['section_name'] ?? 'Unknown') ?>
                                    <br><small>Grade <?= $assignment['section_grade_level'] ?? 'N/A' ?></small>
                                </td>
                                <td><?= esc($assignment['school_year'] ?? 'Unknown') ?></td>
                                <td><?= $assignment['created_at'] ? date('M d, Y', strtotime($assignment['created_at'])) : 'N/A' ?></td>
                                <td>
                                    <span class="status-badge status-<?= $assignment['is_active'] ? 'active' : 'inactive' ?>">
                                        <?= $assignment['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/teachers/remove-assignment/<?= $assignment['id'] ?>" 
                                       class="btn btn-danger" 
                                       style="padding: 5px 10px; font-size: 12px;"
                                       onclick="return confirm('Remove this assignment?')">
                                        🗑️ Remove
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No teacher assignments found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function filterAssignments() {
            const teacherFilter = document.getElementById('filter_teacher').value.toLowerCase();
            const subjectFilter = document.getElementById('filter_subject').value.toLowerCase();
            const sectionFilter = document.getElementById('filter_section').value.toLowerCase();
            const statusFilter = document.getElementById('filter_status').value.toLowerCase();
            const searchText = document.getElementById('search_box').value.toLowerCase();
            
            const table = document.getElementById('assignmentsTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const teacher = row.getAttribute('data-teacher').toLowerCase();
                const subject = row.getAttribute('data-subject').toLowerCase();
                const section = row.getAttribute('data-section').toLowerCase();
                const status = row.getAttribute('data-status').toLowerCase();
                const searchData = row.getAttribute('data-search-text');
                
                let showRow = true;
                
                // Filter by teacher
                if (teacherFilter && !teacher.includes(teacherFilter)) {
                    showRow = false;
                }
                
                // Filter by subject
                if (subjectFilter && !subject.includes(subjectFilter)) {
                    showRow = false;
                }
                
                // Filter by section
                if (sectionFilter && !section.includes(sectionFilter)) {
                    showRow = false;
                }
                
                // Filter by status
                if (statusFilter && status !== statusFilter) {
                    showRow = false;
                }
                
                // Filter by search text
                if (searchText && !searchData.includes(searchText)) {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            }
            
            // Update row count or show no results message
            updateRowCount();
        }
        
        function clearFilters() {
            document.getElementById('filter_teacher').value = '';
            document.getElementById('filter_subject').value = '';
            document.getElementById('filter_section').value = '';
            document.getElementById('filter_status').value = '';
            document.getElementById('search_box').value = '';
            
            filterAssignments();
        }
        
        function updateRowCount() {
            const table = document.getElementById('assignmentsTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            let visibleCount = 0;
            
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].style.display !== 'none') {
                    visibleCount++;
                }
            }
            
            // You can add a counter display here if needed
            console.log('Visible rows:', visibleCount);
        }
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterAssignments();
        });
    </script>
</body>
</html>
