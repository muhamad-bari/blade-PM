<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $tasksDue = Project::whereDate('deadline', today())->count();
        $overdue = Project::whereDate('deadline', '<', today())->where('status', '!=', 'completed')->count();
        
        // Pinned projects for the list
        $pinnedProjects = Project::where('is_pinned', true)->with('leader')->take(5)->get();

        // Chart Data
        $projectStatusCounts = [
            'pending' => Project::where('status', 'pending')->count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
        ];

        // Calendar Data
        $calendarEvents = Project::all()->map(function ($project) {
            return [
                'title' => $project->title,
                'start' => $project->deadline->format('Y-m-d'),
                'url' => route('projects.show', $project),
                'color' => match($project->priority) {
                    'high' => '#ef4444', // red-500
                    'medium' => '#eab308', // yellow-500
                    'low' => '#22c55e', // green-500
                    default => '#3b82f6',
                }
            ];
        });
        
        // All projects for modals (if we want to edit any project from dashboard, though usually dashboard only shows pinned. 
        // But user asked for "Create/Edit Project modal form" on dashboard. 
        // If we only show pinned projects, we only need modals for them. 
        // If the user wants to manage ALL projects from dashboard, we'd need a full list. 
        // Assuming "Pinned Projects carousel (or list)" implies we interact with those.
        // For "Create", it's global.
        $employees = Employee::all(); // For Create/Edit forms

        return view('dashboard', compact('totalProjects', 'tasksDue', 'overdue', 'pinnedProjects', 'projectStatusCounts', 'calendarEvents', 'employees'));
    }
}
