<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class ProjectExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $priority;

    public function __construct($startDate = null, $endDate = null, $priority = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->priority = $priority;
    }

    public function query()
    {
        $query = Project::query()->with(['leader', 'employees']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('deadline', [$this->startDate, $this->endDate]);
        }

        if ($this->priority && $this->priority !== 'all') {
            $query->where('priority', $this->priority);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Description',
            'Status',
            'Priority',
            'Deadline',
            'Leader',
            'Team Members',
            'Created At',
        ];
    }

    public function map($project): array
    {
        return [
            $project->id,
            $project->title,
            $project->description,
            ucfirst(str_replace('_', ' ', $project->status)),
            ucfirst($project->priority),
            $project->deadline->format('Y-m-d'),
            $project->leader ? $project->leader->name : 'N/A',
            $project->employees->pluck('name')->implode(', '),
            $project->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
