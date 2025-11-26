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
        // Calendar Data
        $calendarEvents = Project::all()->map(function ($project) {
            // Determine color based on status and priority
            $color = '#3b82f6'; // Default Blue (In Progress)
            
            if ($project->priority === 'high') {
                $color = '#ef4444'; // Red (Urgent)
            } elseif ($project->status === 'completed') {
                $color = '#22c55e'; // Green
            } elseif ($project->status === 'pending') {
                $color = '#eab308'; // Yellow
            } elseif ($project->status === 'in_progress') {
                $color = '#3b82f6'; // Blue
            }

            return [
                'title' => $project->title,
                'start' => $project->created_at->format('Y-m-d'),
                'end' => $project->deadline->addDay()->format('Y-m-d'), // Exclusive end date for range
                'url' => route('projects.show', $project),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'allDay' => true,
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
