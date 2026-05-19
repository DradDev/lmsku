<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-4xl mx-auto px-6">

            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 mb-2">
                    Account Settings
                </p>

                <h1 class="text-3xl font-bold text-slate-900">
                    My Profile
                </h1>

                <p class="mt-2 text-slate-500">
                    Perbarui nama, email, avatar, dan password akun Anda.
                </p>
            </div>

            @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-700">
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <form action="{{ route('profile.update') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">
                                Full Name
                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:outline-none"
                                required>

                            @error('name')
                            <p class="mt-2 text-xs text-rose-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">
                                Email
                            </label>

                            <input type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:outline-none"
                                required>

                            <p class="mt-2 text-xs text-slate-500">
                                Email harus unik dan tidak boleh sama dengan user lain.
                            </p>

                            @error('email')
                            <p class="mt-2 text-xs text-rose-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-slate-700">
                                Avatar
                            </label>

                            <input type="file"
                                name="avatar"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="w-full rounded-2xl border border-slate-300 bg-white p-3 text-slate-900 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-indigo-700">

                            <p class="mt-2 text-xs text-slate-500">
                                Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.
                            </p>

                            @error('avatar')
                            <p class="mt-2 text-xs text-rose-600">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div class="border-t border-slate-200 pt-6">
                            <h2 class="text-lg font-semibold text-slate-900 mb-1">
                                Change Password
                            </h2>

                            <p class="text-sm text-slate-500 mb-4">
                                Kosongkan bagian password jika tidak ingin mengubah password.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700">
                                        New Password
                                    </label>

                                    <input type="password"
                                        name="password"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:outline-none"
                                        autocomplete="new-password">

                                    @error('password')
                                    <p class="mt-2 text-xs text-rose-600">
                                        {{ $message }}
                                    </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700">
                                        Confirm Password
                                    </label>

                                    <input type="password"
                                        name="password_confirmation"
                                        class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:outline-none"
                                        autocomplete="new-password">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-slate-900 mb-5">
                            Profile Preview
                        </h2>

                        <div class="flex flex-col items-center text-center">
                            @if ($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}"
                                alt="{{ $user->name }}"
                                class="h-24 w-24 rounded-full object-cover border border-slate-200 shadow-sm">
                            @else
                            <div class="h-24 w-24 rounded-full bg-indigo-600 text-white flex items-center justify-center text-3xl font-bold shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @endif

                            <h3 class="mt-4 text-lg font-semibold text-slate-900">
                                {{ $user->name }}
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ ucfirst($user->role) }}
                            </p>

                            <p class="mt-1 text-sm text-slate-500">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-indigo-200 bg-indigo-50 p-6 shadow-sm">
                        <h2 class="text-xl font-semibold text-indigo-900 mb-3">
                            Tips
                        </h2>

                        <p class="text-sm leading-6 text-indigo-700">
                            Gunakan avatar yang jelas dan nama yang rapi agar akun Anda terlihat lebih profesional seperti platform LMS modern.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>