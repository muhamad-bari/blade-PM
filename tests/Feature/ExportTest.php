<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProjectExport;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_projects()
    {
        Excel::fake();

        $admin = User::factory()->create(['role' => 'admin']);
        
        // Create some projects to export
        Project::factory()->count(5)->create();

        $response = $this->actingAs($admin)
            ->get(route('projects.export'));

        $response->assertStatus(200);
        
        Excel::assertDownloaded('projects_' . now()->format('Y-m-d_H-i-s') . '.xlsx', function(ProjectExport $export) {
            return true;
        });
    }

    public function test_non_admin_cannot_export_projects()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)
            ->get(route('projects.export'));

        $response->assertStatus(403);
    }
}
