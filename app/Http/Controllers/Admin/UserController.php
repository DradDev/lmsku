<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->latest()
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', Rule::in(['admin', 'lecturer', 'student'])],
            'peminatan' => ['nullable', 'string', 'max:255'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'peminatan' => $validated['peminatan'] ?? null,
            'registration_status' => 'approved',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => ['required', Rule::in(['admin', 'lecturer', 'student'])],
            'password' => ['nullable', 'string', 'min:8'],
            'peminatan' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'peminatan' => $validated['peminatan'] ?? null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = bcrypt($validated['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function approve(Request $request, User $user): RedirectResponse
    {
        $note = trim((string) $request->input('reason'));

        $user->update([
            'registration_status' => 'approved',
            'registration_note' => $note !== '' ? $note : 'Registrasi disetujui oleh admin.',
        ]);

        Log::info('User registration approved', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'approved_by' => optional($request->user())->id,
            'reason' => $user->registration_note,
            'notification_message' => "Halo {$user->name}, akun kamu di LMS sudah disetujui admin. "
                . "Silakan login kembali di sini: " . route('login'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Registrasi ' . $user->name . ' berhasil disetujui.');
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ], [
            'reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $user->update([
            'registration_status' => 'rejected',
            'registration_note' => $validated['reason'],
        ]);

        Log::info('User registration rejected', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'rejected_by' => optional($request->user())->id,
            'reason' => $validated['reason'],
            'notification_message' => "Halo {$user->name}, mohon maaf registrasi akun kamu di LMS ditolak. "
                . "Alasan: {$validated['reason']}. "
                . "Silakan coba registrasi kembali di sini: " . route('register'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Registrasi ' . $user->name . ' ditolak.');
    }
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}