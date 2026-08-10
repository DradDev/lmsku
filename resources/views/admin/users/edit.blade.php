<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-3xl mx-auto px-6 py-8">
            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Users
                </a>

                <h1 class="text-3xl font-bold text-slate-900">Change User Role</h1>
                <p class="text-slate-500 mt-2">Perbarui peran (role) dan hak akses untuk pengguna ini. Demi etika profesi & privasi data, nama, email, dan password hanya dapat dikelola secara mandiri oleh pemilik akun.</p>
            </div>

            @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Name <span class="text-xs text-slate-400 font-normal">(Read-only / Privasi Pengguna)</span></label>
                        <input type="text" value="{{ $user->name }}"
                               class="w-full rounded-xl border-slate-200 bg-slate-100 text-slate-600 cursor-not-allowed font-medium"
                               disabled readonly>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email <span class="text-xs text-slate-400 font-normal">(Read-only / Privasi Pengguna)</span></label>
                        <input type="email" value="{{ $user->email }}"
                               class="w-full rounded-xl border-slate-200 bg-slate-100 text-slate-600 cursor-not-allowed font-medium"
                               disabled readonly>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-slate-800 mb-2">User Role / Peran Pengguna</label>
                        <select name="role" id="role"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 font-medium"
                                required>
                            <option value="student" @selected(old('role', $user->role) === 'student')>Student (Mahasiswa)</option>
                            <option value="lecturer" @selected(old('role', $user->role) === 'lecturer')>Lecturer (Dosen Akademik)</option>
                            <option value="vendor" @selected(old('role', $user->role) === 'vendor')>Vendor Eksternal Mitra</option>
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin (Administrator System)</option>
                        </select>
                        <p class="mt-1.5 text-xs text-slate-500">Mengubah role akan secara otomatis menyesuaikan hak akses menu dan portal pengguna.</p>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="submit"
                                class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                            Update Role
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
