<x-app-layout>
    @php
        $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
    @endphp

    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-4xl mx-auto px-6">

            <div class="mb-8">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-600 mb-1">
                    Account Settings
                </p>

                <h1 class="text-3xl font-extrabold text-slate-900">
                    My Profile
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Kelola identitas akun, foto profil, dan keamanan password Anda.
                </p>
            </div>

            @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700">
                <ul class="list-disc pl-5 text-xs font-semibold space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- FORM SECTION -->
                <div class="lg:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <form action="{{ route('profile.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- 1. FOTO PROFIL / AVATAR WITH LIVE FRAME PREVIEW -->
                        <div class="border-b border-slate-100 pb-6">
                            <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700">
                                Foto Profil / Avatar
                            </label>

                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                                <!-- CIRCULAR AVATAR FRAME -->
                                <div class="relative shrink-0">
                                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-indigo-200 bg-slate-100 shadow-sm flex items-center justify-center">
                                        <img id="avatar-live-preview"
                                             src="{{ $user->avatar_url ?? '' }}"
                                             alt="{{ $user->name }}"
                                             class="w-full h-full object-cover object-center {{ $user->avatar_url ? '' : 'hidden' }}">
                                        
                                        <div id="avatar-initial-placeholder"
                                             class="w-full h-full bg-gradient-to-tr from-indigo-700 to-indigo-500 text-white flex items-center justify-center text-3xl font-black {{ $user->avatar_url ? 'hidden' : '' }}">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                </div>

                                <!-- UPLOAD INPUT & INSTRUCTIONS -->
                                <div class="flex-1 space-y-2 text-center sm:text-left w-full">
                                    <input type="file"
                                        id="avatar-input"
                                        name="avatar"
                                        accept="image/jpeg,image/png,image/jpg,image/webp"
                                        onchange="previewAvatar(this)"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 p-2 text-xs text-slate-900 file:mr-3 file:rounded-xl file:border-0 file:bg-indigo-600 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-indigo-700 cursor-pointer">

                                    <p class="text-[11px] text-slate-500 leading-relaxed">
                                        Format file: <strong>JPG, JPEG, PNG, WEBP</strong> (Maksimal 2MB). Foto akan diposisikan di tengah dan dipotong proporsional sesuai bingkai lingkaran avatar.
                                    </p>

                                    @if($user->avatar)
                                    <div class="pt-1">
                                        <label class="inline-flex items-center gap-2 text-xs text-rose-600 font-semibold cursor-pointer">
                                            <input type="checkbox" name="remove_avatar" value="1" id="remove-avatar-checkbox" onchange="toggleRemoveAvatar(this)" class="rounded text-rose-600 focus:ring-rose-500">
                                            <span>Hapus foto profil saat ini (gunakan inisial nama)</span>
                                        </label>
                                    </div>
                                    @endif

                                    @error('avatar')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 2. NAMA LENGKAP -->
                        <div>
                            <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700">
                                Nama Lengkap
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:outline-none"
                                required>

                            @error('name')
                            <p class="mt-1 text-xs text-rose-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- 3. EMAIL -->
                        <div>
                            <label class="block mb-2 text-xs font-bold uppercase tracking-wider text-slate-700">
                                Alamat Email
                            </label>

                            <input type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 focus:border-indigo-500 focus:outline-none"
                                required>

                            @error('email')
                            <p class="mt-1 text-xs text-rose-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <!-- 4. GANTI PASSWORD -->
                        <div class="border-t border-slate-100 pt-6">
                            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-900 mb-1">
                                Ganti Password
                            </h2>

                            <p class="text-xs text-slate-500 mb-4">
                                Kosongkan kolom password jika Anda tidak ingin mengubah password akun saat ini.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-slate-700">
                                        Password Baru
                                    </label>

                                    <input type="password"
                                        name="password"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                        autocomplete="new-password">

                                    @error('password')
                                    <p class="mt-1 text-xs text-rose-600">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-1 text-xs font-semibold text-slate-700">
                                        Konfirmasi Password Baru
                                    </label>

                                    <input type="password"
                                        name="password_confirmation"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                        autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="rounded-2xl bg-indigo-600 px-6 py-3 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- SIDEBAR PREVIEW SECTION -->
                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-5 text-center">
                            Pratinjau Profil
                        </h2>

                        <div class="flex flex-col items-center text-center">
                            <!-- SIDEBAR CIRCULAR FRAME -->
                            <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-indigo-100 shadow-md bg-slate-100 flex items-center justify-center">
                                <img id="sidebar-avatar-preview"
                                     src="{{ $user->avatar_url ?? '' }}"
                                     alt="{{ $user->name }}"
                                     class="w-full h-full object-cover object-center {{ $user->avatar_url ? '' : 'hidden' }}">
                                
                                <div id="sidebar-avatar-placeholder"
                                     class="w-full h-full bg-gradient-to-tr from-indigo-700 to-indigo-500 text-white flex items-center justify-center text-3xl font-black {{ $user->avatar_url ? 'hidden' : '' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>

                            <h3 class="mt-4 text-base font-bold text-slate-900">
                                {{ $user->name }}
                            </h3>

                            <span class="mt-1 px-3 py-0.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase">
                                {{ $user->role }}
                            </span>

                            <p class="mt-2 text-xs text-slate-500">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-indigo-100 bg-indigo-50/70 p-5 shadow-xs">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-900 mb-2">
                            Panduan Foto Profil
                        </h3>

                        <p class="text-xs leading-relaxed text-indigo-800">
                            Untuk hasil terbaik, gunakan foto rasio 1:1 (persegi) dengan posisi wajah terpusat di tengah agar pas memenuhi bingkai avatar lingkaran.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- JAVASCRIPT LIVE PREVIEW SCRIPT -->
    <script>
        const initialAvatarUrl = "{{ $user->avatar_url ?? '' }}";

        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];

                if (file.size > 2097152) {
                    alert('Ukuran file foto maksimal adalah 2MB.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const liveImg = document.getElementById('avatar-live-preview');
                    const initialBox = document.getElementById('avatar-initial-placeholder');
                    const sideImg = document.getElementById('sidebar-avatar-preview');
                    const sideBox = document.getElementById('sidebar-avatar-placeholder');

                    if (liveImg) {
                        liveImg.src = e.target.result;
                        liveImg.classList.remove('hidden');
                    }
                    if (initialBox) {
                        initialBox.classList.add('hidden');
                    }

                    if (sideImg) {
                        sideImg.src = e.target.result;
                        sideImg.classList.remove('hidden');
                    }
                    if (sideBox) {
                        sideBox.classList.add('hidden');
                    }

                    const removeCheckbox = document.getElementById('remove-avatar-checkbox');
                    if (removeCheckbox) {
                        removeCheckbox.checked = false;
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function toggleRemoveAvatar(checkbox) {
            const liveImg = document.getElementById('avatar-live-preview');
            const initialBox = document.getElementById('avatar-initial-placeholder');
            const sideImg = document.getElementById('sidebar-avatar-preview');
            const sideBox = document.getElementById('sidebar-avatar-placeholder');
            const fileInput = document.getElementById('avatar-input');

            if (checkbox.checked) {
                if (fileInput) fileInput.value = '';
                if (liveImg) liveImg.classList.add('hidden');
                if (initialBox) initialBox.classList.remove('hidden');
                if (sideImg) sideImg.classList.add('hidden');
                if (sideBox) sideBox.classList.remove('hidden');
            } else {
                if (initialAvatarUrl) {
                    if (liveImg) {
                        liveImg.src = initialAvatarUrl;
                        liveImg.classList.remove('hidden');
                    }
                    if (initialBox) initialBox.classList.add('hidden');
                    if (sideImg) {
                        sideImg.src = initialAvatarUrl;
                        sideImg.classList.remove('hidden');
                    }
                    if (sideBox) sideBox.classList.add('hidden');
                }
            }
        }
    </script>
</x-app-layout>