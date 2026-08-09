<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        $mainSkills = Skill::with(['tags', 'children' => function ($query) {
                $query->with('tags')->orderBy('name');
            }])
            ->whereNull('parent_id')
            ->orderBy('name')
            ->get();

        $orphanSkills = Skill::whereNotNull('parent_id')
            ->whereDoesntHave('parent')
            ->with('tags')
            ->orderBy('name')
            ->get();

        return view('admin.skills.index', compact('mainSkills', 'orphanSkills'));
    }

    public function show(Skill $skill): View
    {
        $skill->load(['tags', 'parent', 'children.tags']);

        return view('admin.skills.show', compact('skill'));
    }

    public function create(): View
    {
        $parentSkills = Skill::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.skills.create', compact('parentSkills'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:skills,name'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:skills,id'],
        ]);

        Skill::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Skill berhasil ditambahkan.');
    }

    public function edit(Skill $skill): View
    {
        $parentSkills = Skill::whereNull('parent_id')
            ->where('id', '!=', $skill->id)
            ->orderBy('name')
            ->get();

        return view('admin.skills.edit', compact('skill', 'parentSkills'));
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:skills,name,' . $skill->id],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'exists:skills,id'],
        ]);

        if ((int) ($validated['parent_id'] ?? 0) === (int) $skill->id) {
            return back()
                ->withErrors(['parent_id' => 'Skill tidak boleh menjadi parent untuk dirinya sendiri.'])
                ->withInput();
        }

        $skill->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Skill berhasil diperbarui.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()
            ->route('admin.skills.index')
            ->with('success', 'Skill berhasil dihapus.');
    }
}