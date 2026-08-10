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

    public function create(): RedirectResponse
    {
        return redirect()->route('admin.users.index')->with('info', 'Registrasi pengguna dilakukan mandiri oleh pengguna.');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('admin.users.index')->with('info', 'Registrasi pengguna dilakukan mandiri oleh pengguna.');
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
            'role' => ['required', Rule::in(['admin', 'lecturer', 'student', 'vendor'])],
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Role pengguna ' . $user->name . ' berhasil diubah.');
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
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update([
            'registration_status' => 'rejected',
            'registration_note' => $validated['reason'] ?? 'Registration rejected by administrator.',
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