<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Models\ActivityLog;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['leader', 'employees'])->latest()->paginate(10);
        $employees = Employee::all();
        return view('projects.index', compact('projects', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::all();
        return view('projects.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());
        
        if ($request->has('employee_ids')) {
            $project->employees()->attach($request->employee_ids);
        }

        ActivityLog::create([
            'description' => 'Project created: ' . $project->title . ' by ' . auth()->user()->name,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['leader', 'employees']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $employees = Employee::all();
        return view('projects.edit', compact('project', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        if ($request->has('employee_ids')) {
            $project->employees()->sync($request->employee_ids);
        }

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    /**
     * Unpin the specified project.
     */
    public function unpin(Project $project)
    {
        $project->update(['is_pinned' => false]);
        return redirect()->route('dashboard')->with('success', 'Project unpinned successfully.');
    }
}
