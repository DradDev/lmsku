<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectParticipation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::with(['user', 'skills', 'tags', 'participations'])
            ->withCount('participations');

        // Filter Creator / Provider Type
        if ($request->filled('provider_type')) {
            if ($request->provider_type === 'vendor') {
                $query->whereHas('user', function ($q) {
                    $q->where('role', 'vendor');
                });
            } elseif ($request->provider_type === 'lecturer') {
                $query->whereHas('user', function ($q) {
                    $q->where('role', 'lecturer');
                });
            }
        }

        // Filter Published Status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->get();

        // Statistics
        $totalProjects = Project::count();
        $publishedProjects = Project::where('is_published', true)->count();
        $vendorProjects = Project::whereHas('user', fn($q) => $q->where('role', 'vendor'))->count();
        $lecturerProjects = Project::whereHas('user', fn($q) => $q->where('role', 'lecturer'))->count();
        $activeParticipations = ProjectParticipation::where('status', 'in_progress')->count();

        return view('admin.projects.index', compact(
            'projects',
            'totalProjects',
            'publishedProjects',
            'vendorProjects',
            'lecturerProjects',
            'activeParticipations'
        ));
    }

    public function show(Project $project): View
    {
        $project->load([
            'user',
            'skills',
            'tags',
            'participations.user',
            'comments.user'
        ]);

        return view('admin.projects.show', compact('project'));
    }

    public function togglePublish(Project $project): RedirectResponse
    {
        $project->update([
            'is_published' => !$project->is_published,
        ]);

        $statusMsg = $project->is_published ? 'dipublikasikan (Aktif)' : 'ditangguhkan / di-suspend (Read-Only)';

        return redirect()
            ->back()
            ->with('success', "Status rilis project '{$project->title}' berhasil diperbarui menjadi {$statusMsg}.");
    }

    public function destroy(Project $project): RedirectResponse
    {
        if ($project->participations()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Tidak dapat menghapus project yang sudah memiliki partisipasi mahasiswa.');
        }

        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }
}
