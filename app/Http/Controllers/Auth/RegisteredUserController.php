<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Ambil 6 skill utama (tanpa parent) untuk dropdown peminatan
        $skills = Skill::whereNull('parent_id')->orderBy('name')->get();

        return view('auth.register', compact('skills'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,lecturer'],
        ];

        // Peminatan wajib diisi jika role = student (bisa 1 atau banyak pilihan)
        if ($request->role === 'student') {
            $rules['peminatan'] = ['required'];
        }

        $request->validate($rules);

        $peminatanString = null;
        if ($request->role === 'student' && !empty($request->peminatan)) {
            if (is_array($request->peminatan)) {
                $peminatanString = implode(', ', array_filter($request->peminatan));
            } else {
                $peminatanString = (string) $request->peminatan;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'peminatan' => $peminatanString,
            'registration_status' => 'pending',
        ]);

        event(new Registered($user));

        // Redirect ke halaman login setelah register
        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan tunggu approval admin untuk login.');
    }
}
