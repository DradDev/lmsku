<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        // Skill Utama untuk peminatan student
        $skills = Skill::orderBy('name')->get();

        // Daftar Institusi Resmi untuk dropdown vendor mitra
        $institutions = Institution::where('is_verified', true)->orderBy('name')->get();

        return view('auth.register', compact('skills', 'institutions'));
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
            'role' => ['required', 'in:student,lecturer,vendor'],
        ];

        // Validasi opsional untuk Student
        if ($request->role === 'student') {
            $rules['peminatan'] = ['nullable'];
        }

        // Validasi terstruktur untuk Vendor
        if ($request->role === 'vendor') {
            $rules['vendor_type'] = ['required', 'in:company,individual'];

            if ($request->input('vendor_type') === 'company') {
                $rules['institution_mode'] = ['required', 'in:existing,new'];

                if ($request->input('institution_mode') === 'existing') {
                    $rules['institution_id'] = ['required', 'exists:institutions,id'];
                } elseif ($request->input('institution_mode') === 'new') {
                    $rules['new_institution_name'] = ['required', 'string', 'max:255'];
                    $rules['new_institution_code'] = ['nullable', 'string', 'max:15'];
                }
            }
        }

        $request->validate($rules);

        // 1. Olah Peminatan Student
        $peminatanString = null;
        if ($request->role === 'student' && !empty($request->peminatan)) {
            if (is_array($request->peminatan)) {
                $peminatanString = implode(', ', array_filter($request->peminatan));
            } else {
                $peminatanString = (string) $request->peminatan;
            }
        }

        // 2. Olah Data Institusi Vendor
        $institutionId = null;
        $institutionType = null;

        if ($request->role === 'vendor') {
            $institutionType = $request->input('vendor_type');

            if ($institutionType === 'company') {
                if ($request->input('institution_mode') === 'existing') {
                    $institutionId = (int) $request->input('institution_id');
                } elseif ($request->input('institution_mode') === 'new') {
                    $rawName = trim($request->input('new_institution_name'));
                    $rawCode = trim($request->input('new_institution_code'));

                    // Auto-slug
                    $slug = Str::slug($rawName);
                    if (Institution::where('slug', $slug)->exists()) {
                        $slug .= '-' . Str::random(4);
                    }

                    // Tentukan kode inisial yang unik
                    $code = !empty($rawCode)
                        ? strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $rawCode))
                        : Institution::generateUniqueCode($rawName);

                    // Pastikan kode benar-benar unik di DB
                    if (Institution::where('code', $code)->exists()) {
                        $code = Institution::generateUniqueCode($rawName);
                    }

                    $institution = Institution::create([
                        'name' => $rawName,
                        'slug' => $slug,
                        'code' => $code,
                        'type' => 'company',
                        'is_verified' => true,
                    ]);

                    $institutionId = $institution->id;
                }
            } else {
                // Perorangan (individual)
                $institutionId = null;
            }
        }

        // 3. Bentuk Akun Pengguna Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'peminatan' => $peminatanString,
            'institution_id' => $institutionId,
            'institution_type' => $institutionType,
            'registration_status' => 'pending',
        ]);

        event(new Registered($user));

        // Redirect ke halaman login setelah register
        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan tunggu approval admin untuk login.');
    }
}
