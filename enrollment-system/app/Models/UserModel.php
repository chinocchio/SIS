<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'username', 'email', 'password', 'role', 'first_name', 
        'last_name', 'is_active', 'last_login'
    ];
    
    protected $useTimestamps = true;
    
    // Validation rules
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[admin,registrar,teacher,student]',
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]'
    ];
    
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)
                    ->where('is_active', 1)
                    ->findAll();
    }
    
    public function getActiveUsers()
    {
        return $this->where('is_active', 1)->findAll();
    }
    
    public function createUser($data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->insert($data);
    }
    
    public function updateUser($id, $data)
    {
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // Don't update password if empty
        }
        
        return $this->update($id, $data);
    }
    
    public function deactivateUser($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }
    
    public function activateUser($id)
    {
        return $this->update($id, ['is_active' => 1]);
    }
    
    public function getUserWithProfile($id)
    {
        $user = $this->find($id);
        if ($user) {
            $db = \Config\Database::connect();
            $profile = $db->table('user_profiles')
                          ->where('user_id', $id)
                          ->get()
                          ->getRowArray();
            
            if ($profile) {
                $user = array_merge($user, $profile);
            }
        }
        
        return $user;
    }
}
