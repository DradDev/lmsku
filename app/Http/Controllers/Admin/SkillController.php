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
        $skills = Skill::with('tags')
            ->orderBy('name')
            ->get();

        return view('admin.skills.index', compact('skills'));
    }

    public function show(Skill $skill): View
    {
        $skill->load('tags');

        return view('admin.skills.show', compact('skill'));
    }

    public function create(): View
    {
        return view('admin.skills.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:skills,name'],
            'description' => ['nullable', 'string'],
        ]);

        $skill = Skill::create([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.skills.show', $skill)
            ->with('success', "Main Skill '{$skill->name}' berhasil ditambahkan. Anda dapat langsung mengelola tag sub-topiknya di bawah.");
    }

    public function edit(Skill $skill): View
    {
        return view('admin.skills.edit', compact('skill'));
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:skills,name,' . $skill->id],
            'description' => ['nullable', 'string'],
        ]);

        $skill->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', "Data Main Skill '{$skill->name}' berhasil diperbarui.");
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skillName = $skill->name;
        $skill->delete();

        return redirect()
            ->route('admin.skills.index')
            ->with('success', "Main Skill '{$skillName}' berhasil dihapus.");
    }
}