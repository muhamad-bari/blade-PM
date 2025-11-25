<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'position', 'email', 'phone', 'avatar_url'];

    public function ledProjects()
    {
        return $this->hasMany(Project::class, 'leader_employee_id');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'employee_project')->withPivot('role');
    }
}
