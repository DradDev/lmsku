<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'skill_id' => ['required', 'exists:skills,id'],
        ]);

        Tag::create([
            'name' => $validated['name'],
            'skill_id' => $validated['skill_id'],
        ]);

        return back()->with('success', 'Tag berhasil ditambahkan ke Skill ini.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return back()->with('success', 'Tag berhasil dihapus.');
    }
}