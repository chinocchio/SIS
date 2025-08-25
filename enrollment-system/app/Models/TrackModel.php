<?php

namespace App\Models;

use CodeIgniter\Model;

class TrackModel extends Model
{
    protected $table = 'tracks';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'level', 'description', 'is_active'
    ];
    
    protected $useTimestamps = true;
    
    public function getTracksByLevel($level)
    {
        return $this->where('level', $level)
                    ->where('is_active', 1)
                    ->findAll();
    }
    
    public function getAllActiveTracks()
    {
        return $this->where('is_active', 1)
                    ->orderBy('level', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
    
    public function getTrackWithDetails($id)
    {
        return $this->find($id);
    }
}
