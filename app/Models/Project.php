<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'leader_employee_id', 'is_pinned', 'priority', 'deadline', 'status'];

    protected $casts = [
        'is_pinned' => 'boolean',
        'deadline' => 'date',
    ];

    public function leader()
    {
        return $this->belongsTo(Employee::class, 'leader_employee_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_project')->withPivot('role');
    }
}
