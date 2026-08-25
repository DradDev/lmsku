<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseOffering;
use App\Models\MasterCourse;
use App\Models\Skill;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $vendorId = Auth::id();

        // 1. Pastikan seluruh legacy course milik vendor terhubung ke MasterCourse
        $legacyWithoutMaster = Course::where('lecturer_id', $vendorId)
            ->whereNull('master_course_id')
            ->get();

        foreach ($legacyWithoutMaster as $lc) {
            $mc = MasterCourse::firstOrCreate(
                [
                    'name' => $lc->name,
                    'user_id' => $vendorId,
                ],
                [
                    'code' => 'VMC-' . strtoupper(Str::random(6)),
                    'description' => $lc->description,
                    'level' => $lc->level ?? 'Beginner',
                    'certificate_threshold' => $lc->certificate_threshold ?? 75,
                ]
            );
            $lc->update(['master_course_id' => $mc->id]);
        }

        // 2. Ambil MasterCourse milik Vendor beserta angkatan batch dan relasinya
        $masterCourses = MasterCourse::with([
            'skills',
            'tags',
            'materials',
            'quizzes',
            'courses.enrollments',
        ])
        ->where('user_id', $vendorId)
        ->latest()
        ->get();

        // Ambil data courses untuk fallback & kalkulasi
        $allBatches = Course::with(['materials', 'quizzes', 'enrollments'])
            ->where('lecturer_id', $vendorId)
            ->latest()
            ->get();

        $activeMasterCourses = $masterCourses->filter(function ($mc) {
            // Aktif jika memiliki setidaknya 1 batch yang tidak diarsipkan
            return $mc->courses->where('is_archived', false)->count() > 0 || $mc->courses->isEmpty();
        })->values();

        $archivedMasterCourses = $masterCourses->filter(function ($mc) {
            return $mc->courses->isNotEmpty() && $mc->courses->every(fn($c) => (bool)$c->is_archived);
        })->values();

        return view('vendor.courses.index', compact(
            'masterCourses',
            'activeMasterCourses',
            'archivedMasterCourses',
            'allBatches'
        ));
    }

    public function toggleArchive(CourseOffering $course): RedirectResponse
    {
        if (($course->lecturer_id ?? $course->user_id) !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke course ini.');
        }

        $newStatus = !$course->is_archived;
        $course->update(['is_archived' => $newStatus]);

        $statusLabel = $newStatus ? 'diarsipkan (Draft Bank)' : 'diaktifkan dan dibuka kembali';

        return back()->with('success', "Status Angkatan '{$course->batch_name}' pada '{$course->name}' berhasil {$statusLabel}.");
    }

    public function create(): View
    {
        $skills = Skill::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('vendor.courses.create', compact('skills', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'batch_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'is_archived' => ['nullable', 'boolean'],
            'skill_ids' => ['required', 'array', 'min:1'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        // 1. Create or find parent MasterCourse for Vendor (Strict 3NF)
        $masterCourse = MasterCourse::firstOrCreate(
            [
                'name' => $validated['name'],
                'user_id' => Auth::id(),
            ],
            [
                'code' => 'VMC-' . strtoupper(Str::random(6)),
                'description' => $validated['description'],
                'level' => $validated['level'],
                'certificate_threshold' => $validated['certificate_threshold'],
            ]
        );

        $skillsData = [];
        $masterSkillsData = [];
        foreach ($validated['skill_ids'] as $sId) {
            $skillsData[$sId] = ['is_main' => true, 'weight' => 1.00];
            $masterSkillsData[$sId] = ['is_main' => true];
        }
        $masterCourse->skills()->sync($masterSkillsData);
        if (!empty($validated['tag_ids'])) {
            $masterCourse->tags()->sync($validated['tag_ids']);
        }

        // 2. Buat Inaugural Batch (CourseOffering type=vendor)
        $course = \App\Models\CourseOffering::create([
            'master_course_id'      => $masterCourse->id,
            'type'                  => 'vendor',
            'lecturer_id'           => Auth::id(),
            'academic_term_id'      => null,
            'section_name'          => $validated['batch_name'] ?? 'Batch 1 - 2026',
            'capacity'              => 40,
            'certificate_threshold' => $validated['certificate_threshold'] ?? 75,
            'status'                => 'published',
            'is_archived'           => false,
            'start_date'            => !empty($validated['start_date']) ? $validated['start_date'] : now(),
            'end_date'              => !empty($validated['end_date']) ? $validated['end_date'] : now()->addWeeks($validated['duration_weeks'] ?? 4),
        ]);

        return redirect()
            ->route('vendor.courses.show', $course)
            ->with('success', 'Program Sertifikasi Industri & Batch Perdana berhasil dibuat.');
    }

    public function show($course): View
    {
        $vendorId = Auth::id();
        $courseOffering = is_numeric($course)
            ? CourseOffering::with('masterCourse')->find($course)
            : ($course instanceof CourseOffering ? $course : null);

        // Fallback: Jika $course adalah ID MasterCourse atau bukan CourseOffering langsung milik vendor
        $isOwner = $courseOffering && (
            $courseOffering->lecturer_id === $vendorId ||
            ($courseOffering->masterCourse && $courseOffering->masterCourse->user_id === $vendorId) ||
            Auth::user()->isAdmin()
        );

        if (!$courseOffering || !$isOwner) {
            $masterCourseId = is_numeric($course) ? $course : ($course->id ?? null);
            $foundByMaster = CourseOffering::where('master_course_id', $masterCourseId)
                ->where(function ($q) use ($vendorId) {
                    if (!Auth::user()->isAdmin()) {
                        $q->where('lecturer_id', $vendorId)
                          ->orWhereHas('masterCourse', function ($mc) use ($vendorId) {
                              $mc->where('user_id', $vendorId);
                          });
                    }
                })
                ->latest()
                ->first();

            if ($foundByMaster) {
                $courseOffering = $foundByMaster;
            }
        }

        abort_unless($courseOffering, 404, 'Course sertifikasi tidak ditemukan.');

        $course = $courseOffering;

        $isCourseOwner = ($course->lecturer_id === $vendorId) ||
            ($course->masterCourse && $course->masterCourse->user_id === $vendorId) ||
            Auth::user()->isAdmin();

        if (!$isCourseOwner) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        // Ensure MasterCourse link exists
        if (!$course->master_course_id) {
            $masterCourse = MasterCourse::firstOrCreate(
                [
                    'name' => $course->name,
                    'user_id' => $course->lecturer_id ?? $course->user_id,
                ],
                [
                    'code' => 'VMC-' . strtoupper(Str::random(6)),
                    'description' => $course->description,
                    'level' => $course->level,
                    'certificate_threshold' => $course->certificate_threshold ?? 75,
                ]
            );
            $course->update(['master_course_id' => $masterCourse->id]);
        }

        $course->load([
            'materials',
            'quizzes.questions',
            'enrollments.user',
            'students',
            'skills',
            'tags',
            'masterCourse.materials',
            'masterCourse.quizzes.questions',
            'masterCourse.skills',
            'masterCourse.tags',
        ]);

        // Materi & Kuis terpusat dari MasterCourse & seluruh batch di bawah kurikulum ini
        $masterCourse = $course->masterCourse;
        $masterCourseId = $masterCourse ? $masterCourse->id : $course->master_course_id;
        $offeringIds = CourseOffering::where('master_course_id', $masterCourseId)->pluck('id');

        $materials = \App\Models\Material::where(function ($q) use ($masterCourseId, $offeringIds) {
            $q->where(function ($sub) use ($masterCourseId) {
                $sub->where('materialable_type', \App\Models\MasterCourse::class)
                    ->where('materialable_id', $masterCourseId);
            })->orWhere(function ($sub) use ($offeringIds) {
                $sub->where('materialable_type', \App\Models\CourseOffering::class)
                    ->whereIn('materialable_id', $offeringIds);
            });
        })->get();

        $quizzes = \App\Models\Quiz::with('questions')->where(function ($q) use ($masterCourseId, $offeringIds) {
            $q->where(function ($sub) use ($masterCourseId) {
                $sub->where('quizzable_type', \App\Models\MasterCourse::class)
                    ->where('quizzable_id', $masterCourseId);
            })->orWhere(function ($sub) use ($offeringIds) {
                $sub->where('quizzable_type', CourseOffering::class)
                    ->whereIn('quizzable_id', $offeringIds);
            });
        })->get();

        $students = $course->students;
        $completedStudentCount = $course->enrollments()->where('status', 'completed')->count();

        // Seluruh angkatan batch yang ada pada kurikulum induk ini
        $allBatches = CourseOffering::where('master_course_id', $course->master_course_id)
            ->with(['enrollments'])
            ->withCount('enrollments')
            ->orderBy('created_at')
            ->get();

        $otherBatches = $allBatches->where('id', '!=', $course->id);

        $skills = Skill::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        $retakeRequests = \App\Models\QuizRetakeRequest::with(['user', 'quiz'])
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->where(function ($q) use ($course) {
                $q->where('course_offering_id', $course->id)
                  ->orWhere(function ($sub) use ($course) {
                      $sub->whereNull('course_offering_id')
                          ->whereIn('user_id', $course->enrollments->pluck('user_id'));
                  });
            })
            ->latest()
            ->get();

        return view('vendor.courses.show', compact(
            'course',
            'masterCourse',
            'materials',
            'quizzes',
            'students',
            'completedStudentCount',
            'allBatches',
            'otherBatches',
            'skills',
            'tags',
            'retakeRequests'
        ));
    }

    public function edit(CourseOffering $course): View
    {
        if (($course->lecturer_id ?? $course->user_id) !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $skills = Skill::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('vendor.courses.edit', compact('course', 'skills', 'tags'));
    }

    public function update(Request $request, CourseOffering $course): RedirectResponse
    {
        if (($course->lecturer_id ?? $course->user_id) !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'skill_ids' => ['required', 'array', 'min:1'],
            'skill_ids.*' => ['exists:skills,id'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
            'batch_name' => ['nullable', 'string', 'max:255'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'certificate_threshold' => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        // 1. Update MasterCourse
        if ($course->master_course_id) {
            $masterCourse = MasterCourse::find($course->master_course_id);
            if ($masterCourse) {
                $masterCourse->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'level' => $validated['level'],
                ]);

                $masterSkillsData = [];
                foreach ($validated['skill_ids'] as $sId) {
                    $masterSkillsData[$sId] = ['is_main' => true];
                }
                $masterCourse->skills()->sync($masterSkillsData);

                if (isset($validated['tag_ids'])) {
                    $masterCourse->tags()->sync($validated['tag_ids']);
                }

                // Sinkronkan metadata master ke seluruh batch turunan
                Course::where('master_course_id', $masterCourse->id)->update([
                    'name' => $validated['name'],
                    'description' => $validated['description'],
                    'level' => $validated['level'],
                ]);
            }
        }

        // 2. Update field batch spesifik jika ada
        $batchUpdate = [
            'name' => $validated['name'],
            'description' => $validated['description'],
            'level' => $validated['level'],
        ];

        if (!empty($validated['batch_name'])) {
            $batchUpdate['batch_name'] = $validated['batch_name'];
        }
        if (isset($validated['duration_weeks'])) {
            $batchUpdate['duration_weeks'] = $validated['duration_weeks'];
        }
        if (isset($validated['certificate_threshold'])) {
            $batchUpdate['certificate_threshold'] = $validated['certificate_threshold'];
        }
        if (isset($validated['is_archived'])) {
            $batchUpdate['is_archived'] = $validated['is_archived'];
        }

        $course->update($batchUpdate);

        return redirect()
            ->route('vendor.courses.show', $course)
            ->with('success', 'Informasi Program Sertifikasi Industri berhasil diperbarui.');
    }

    public function updateBatch(Request $request, CourseOffering $course): RedirectResponse
    {
        if (($course->lecturer_id ?? $course->user_id) !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke batch ini.');
        }

        $validated = $request->validate([
            'batch_name' => ['required', 'string', 'max:255'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        $updateData = [
            'section_name' => $validated['batch_name'],
            'certificate_threshold' => $validated['certificate_threshold'],
        ];

        if ($request->has('capacity')) {
            $updateData['capacity'] = $request->filled('capacity') ? (int)$request->capacity : null;
        }

        if ($request->has('is_archived')) {
            $updateData['is_archived'] = (bool)$request->is_archived;
        }

        $startDate = !empty($validated['start_date']) ? \Carbon\Carbon::parse($validated['start_date']) : ($course->start_date ?? now());
        $updateData['start_date'] = $startDate;

        if (!empty($validated['end_date'])) {
            $updateData['end_date'] = \Carbon\Carbon::parse($validated['end_date']);
        } elseif (!empty($validated['duration_weeks'])) {
            $updateData['end_date'] = $startDate->copy()->addWeeks((int)$validated['duration_weeks']);
        }

        $course->update($updateData);

        return redirect()
            ->route('vendor.courses.show', $course)
            ->with('success', "Pengaturan Angkatan '{$course->batch_name}' berhasil diperbarui.");
    }

    public function launchBatch(Request $request, CourseOffering $course): RedirectResponse
    {
        if (($course->lecturer_id ?? $course->user_id) !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $validated = $request->validate([
            'batch_name' => ['required', 'string', 'max:255'],
            'certificate_threshold' => ['required', 'integer', 'min:0', 'max:100'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'duration_weeks' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        // Pastikan MasterCourse ada
        if (!$course->master_course_id) {
            $masterCourse = MasterCourse::firstOrCreate(
                [
                    'name' => $course->name,
                    'user_id' => Auth::id(),
                ],
                [
                    'code' => 'VMC-' . strtoupper(Str::random(6)),
                    'description' => $course->description,
                    'level' => $course->level,
                    'certificate_threshold' => $course->certificate_threshold ?? 75,
                ]
            );
            $course->update(['master_course_id' => $masterCourse->id]);
        }

        $newBatch = \App\Models\CourseOffering::create([
            'master_course_id'      => $course->master_course_id,
            'type'                  => 'vendor',
            'lecturer_id'           => Auth::id(),
            'academic_term_id'      => null,
            'section_name'          => $validated['batch_name'],
            'capacity'              => $request->filled('capacity') ? (int)$request->capacity : 40,
            'start_date'            => !empty($validated['start_date']) ? $validated['start_date'] : now(),
            'end_date'              => !empty($validated['end_date']) ? $validated['end_date'] : now()->addWeeks($validated['duration_weeks'] ?? 4),
            'certificate_threshold' => $validated['certificate_threshold'],
            'status'                => 'published',
            'is_archived'           => false,
        ]);

        return redirect()
            ->route('vendor.courses.show', $newBatch)
            ->with('success', 'Angkatan ' . $newBatch->batch_name . ' berhasil diluncurkan! Seluruh materi dan bank kuis otomatis diwariskan.');
    }

    public function destroy(CourseOffering $course): RedirectResponse
    {
        if (($course->lecturer_id ?? $course->user_id) !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke course sertifikasi ini.');
        }

        $enrollmentCount = $course->enrollments()->count();
        if ($enrollmentCount > 0) {
            return back()->with('error', "Tidak dapat menghapus angkatan '{$course->batch_name}' karena sudah memiliki {$enrollmentCount} mahasiswa terdaftar. Silakan gunakan fitur 'Tutup / Arsip' untuk menonaktifkan pendaftaran tanpa merusak riwayat akademik mahasiswa.");
        }

        $masterCourseId = $course->master_course_id;
        $batchName = $course->batch_name;

        $course->delete();

        $siblingBatch = CourseOffering::where('master_course_id', $masterCourseId)->latest()->first();

        if ($siblingBatch) {
            return redirect()
                ->route('vendor.courses.show', $siblingBatch->id)
                ->with('success', "Angkatan '{$batchName}' berhasil dihapus.");
        }

        return redirect()
            ->route('vendor.courses.index')
            ->with('success', "Angkatan '{$batchName}' berhasil dihapus.");
    }
}
