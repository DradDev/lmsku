<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterCourse;
use App\Models\Category;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\Course;

class MasterCourseController extends Controller
{
    public function index(): View
    {
        $masterCourses = MasterCourse::with(['category', 'skills', 'tags', 'materials', 'quizzes'])
            ->where(function ($q) {
                $q->whereNull('user_id')
                  ->orWhereHas('user', fn($u) => $u->where('role', '!=', 'vendor'));
            })
            ->withCount('offerings')
            ->orderBy('name')
            ->get();

        $vendorMasterCourses = MasterCourse::with([
            'user',
            'category',
            'skills',
            'tags',
            'materials',
            'quizzes',
            'courses.enrollments',
        ])
        ->whereHas('user', function ($query) {
            $query->where('role', 'vendor');
        })
        ->withCount('courses')
        ->orderByDesc('created_at')
        ->get();

        $vendorCourses = $vendorMasterCourses;

        return view('admin.master-courses.index', compact('masterCourses', 'vendorMasterCourses', 'vendorCourses'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.master-courses.create', compact('categories', 'skills', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['nullable', 'string', 'max:50', 'unique:master_courses,code'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'certificate_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $skillIds = $validated['skill_ids'] ?? [];
        $tagIds = $validated['tag_ids'] ?? [];

        if (empty($validated['code'])) {
            $validated['code'] = $this->generateInternalCode($validated['category_id'] ?? null, $skillIds, $validated['level']);
        }

        $masterCourse = MasterCourse::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'level' => $validated['level'],
            'certificate_threshold' => $validated['certificate_threshold'],
            'category_id' => $validated['category_id'] ?? null,
        ]);

        if (!empty($skillIds)) {
            $masterCourse->skills()->sync($skillIds);
        }

        if (!empty($tagIds)) {
            $masterCourse->tags()->sync($tagIds);
        }

        return redirect()
            ->route('admin.master-courses.show', $masterCourse)
            ->with('success', 'Master Course berhasil dibuat dengan kode ' . $masterCourse->code . ' beserta target kompetensi skill & tag.');
    }

    private function generateInternalCode(?int $categoryId, array $skillIds, string $level): string
    {
        $prefix = 'TK';
        $skillCode = '';

        if (!empty($skillIds)) {
            $skills = Skill::whereIn('id', $skillIds)->get();
            if ($skills->count() === 1) {
                $words = explode(' ', trim($skills->first()->name));
                if (count($words) >= 2) {
                    $skillCode = strtoupper(substr($words[0], 0, 2) . substr($words[1], 0, 1));
                } else {
                    $skillCode = strtoupper(substr($words[0], 0, 3));
                }
            } elseif ($skills->count() === 2) {
                $parts = [];
                foreach ($skills as $sk) {
                    $words = explode(' ', trim($sk->name));
                    if (count($words) >= 2) {
                        $parts[] = strtoupper(substr($words[0], 0, 2) . substr($words[1], 0, 1));
                    } else {
                        $parts[] = strtoupper(substr($words[0], 0, 3));
                    }
                }
                $skillCode = implode('-', $parts);
            } else {
                // 3 atau lebih skill -> Interdisciplinary / Multi-Skill
                $skillCode = 'INT';
            }
        }

        // Fallback ke Kategori jika skill tidak dipilih
        if (empty($skillCode) && $categoryId) {
            $category = Category::find($categoryId);
            if ($category && !empty($category->name)) {
                $words = explode(' ', trim($category->name));
                if (count($words) >= 2) {
                    $skillCode = strtoupper(substr($words[0], 0, 2) . substr($words[1], 0, 1));
                } else {
                    $skillCode = strtoupper(substr($words[0], 0, 3));
                }
            }
        }

        if (empty($skillCode)) {
            $skillCode = 'GEN';
        }

        $levelCode = match(strtolower($level)) {
            'beginner' => 'BEG',
            'intermediate' => 'INT',
            'advanced' => 'ADV',
            default => 'BEG',
        };

        $base = "{$prefix}-{$skillCode}-{$levelCode}";
        $count = MasterCourse::where('code', 'LIKE', "{$base}-%")->count() + 1;
        $code = "{$base}-" . sprintf('%03d', $count);

        while (MasterCourse::where('code', $code)->exists()) {
            $count++;
            $code = "{$base}-" . sprintf('%03d', $count);
        }

        return $code;
    }

    public function edit(MasterCourse $masterCourse): View
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.master-courses.edit', compact('masterCourse', 'categories'));
    }

    public function update(Request $request, MasterCourse $masterCourse): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:master_courses,code,' . $masterCourse->id],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'certificate_threshold' => ['required', 'integer', 'min:1', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $masterCourse->update($validated);

        return redirect()
            ->route('admin.master-courses.show', $masterCourse)
            ->with('success', 'Master Course berhasil diperbarui.');
    }

    public function syncCompetencies(Request $request, MasterCourse $masterCourse): RedirectResponse
    {
        $validated = $request->validate([
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $masterCourse->skills()->sync($validated['skill_ids'] ?? []);
        $masterCourse->tags()->sync($validated['tag_ids'] ?? []);

        return redirect()
            ->route('admin.master-courses.show', $masterCourse)
            ->with('success', 'Skill & Tag Target Kompetensi berhasil diperbarui.');
    }

    public function show(MasterCourse $masterCourse, Request $request)
    {
        // If this Master Course belongs to a Vendor and has batches, route to the dedicated Admin Vendor Course & Batch view
        if ($masterCourse->user && $masterCourse->user->role === 'vendor') {
            $latestBatch = $masterCourse->courses()->latest()->first();
            if ($latestBatch) {
                return redirect()->route('admin.courses.show', $latestBatch);
            }
        }

        $masterCourse->load(['category', 'materials', 'quizzes', 'skills', 'tags', 'courses.enrollments.user']);

        // Semesters (Academic Terms)
        $academicTerms = \App\Models\AcademicTerm::orderByDesc('is_active')
            ->orderByDesc('id')
            ->get();

        // Course Offerings for this Master Course
        $courseOfferings = \App\Models\CourseOffering::with(['lecturer', 'academicTerm'])
            ->where('master_course_id', $masterCourse->id)
            ->get();

        // Selected term for filtered offering section
        $selectedTermId = $request->query('term_id', $academicTerms->firstWhere('is_active', true)?->id ?? $academicTerms->first()?->id);
        $selectedTerm = $academicTerms->firstWhere('id', $selectedTermId);

        $selectedOfferings = $courseOfferings->where('academic_term_id', $selectedTermId);

        $totalSemesters = $academicTerms->count();
        $totalOfferings = $courseOfferings->count();
        $materials = $masterCourse->materials;
        $quizzes = $masterCourse->quizzes;
        $categories = Category::orderBy('name')->get();

        // All Skills and Tags for competency assignment
        $allSkills = Skill::orderBy('name')->get();
        $allTags = Tag::with('skill')->orderBy('name')->get();

        $activeTab = $request->query('tab', 'hierarchy');

        $lecturers = \App\Models\User::where('role', 'lecturer')->orderBy('name')->get();

        return view('admin.master-courses.show', compact(
            'masterCourse',
            'academicTerms',
            'courseOfferings',
            'selectedTerm',
            'selectedOfferings',
            'totalSemesters',
            'totalOfferings',
            'materials',
            'quizzes',
            'categories',
            'allSkills',
            'allTags',
            'activeTab',
            'lecturers'
        ));
    }

    public function destroy(MasterCourse $masterCourse): RedirectResponse
    {
        if ($masterCourse->offerings()->count() > 0) {
            return redirect()
                ->route('admin.master-courses.index')
                ->with('error', 'Tidak dapat menghapus Master Course yang sudah memiliki kelas penawaran.');
        }

        $masterCourse->delete();

        return redirect()
            ->route('admin.master-courses.index')
            ->with('success', 'Master Course berhasil dihapus.');
    }
}
