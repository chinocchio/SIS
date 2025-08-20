<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@school.edu',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'registrar',
                'email' => 'registrar@school.edu',
                'password' => password_hash('registrar123', PASSWORD_DEFAULT),
                'role' => 'registrar',
                'first_name' => 'School',
                'last_name' => 'Registrar',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'teacher1',
                'email' => 'teacher1@school.edu',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'teacher',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'username' => 'teacher2',
                'email' => 'teacher2@school.edu',
                'password' => password_hash('teacher123', PASSWORD_DEFAULT),
                'role' => 'teacher',
                'first_name' => 'Maria',
                'last_name' => 'Garcia',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $this->db->table('users')->insertBatch($data);
        
        echo "Default users created successfully!\n";
        echo "Admin: admin / admin123\n";
        echo "Registrar: registrar / registrar123\n";
        echo "Teachers: teacher1 / teacher123, teacher2 / teacher123\n";
    }
}
