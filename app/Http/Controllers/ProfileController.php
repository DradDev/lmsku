<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'password' => [
                'nullable',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->hasFile('avatar')) {
            if (! empty($user->avatar) && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $updateData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profile berhasil diperbarui.');
    }
}
