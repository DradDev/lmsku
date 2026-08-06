<x-guest-layout>
    <div class="w-full max-w-5xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

            <div class="hidden lg:block">
                <div class="max-w-lg">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-600 mb-4">
                        Diponegoro University
                    </p>

                    <h1 class="text-5xl font-bold leading-tight text-slate-900">
                        COMPRO System
                    </h1>

                    <p class="mt-5 text-lg leading-8 text-slate-600">
                        Talent Development and Talent Matching platform for TEKKOM empowering
                        students, Author/Vendors, and admins to manage competency skills, interests, quizzes,
                        projects, and digital portfolios in one place.
                    </p>

                    <div class="mt-8 grid grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-white/60 bg-white/70 p-4 shadow-sm backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Talent Development</p>
                            <p class="mt-2 text-sm font-medium text-slate-700">
                                Access learning materials, quizzes, and accumulated skill competency profiles.
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/60 bg-white/70 p-4 shadow-sm backdrop-blur">
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Talent Matching</p>
                            <p class="mt-2 text-sm font-medium text-slate-700">
                                Project qualification matching and verified student talent recruitment.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full max-w-md mx-auto">
                <div class="rounded-3xl border border-white/70 bg-white/85 p-8 shadow-2xl shadow-slate-200/70 backdrop-blur">
                    <div class="text-center mb-8">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-violet-600 text-white shadow-lg">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                            </svg>
                        </div>

                        <h1 class="mt-5 text-3xl font-bold text-slate-900">
                            COMPRO
                        </h1>

                        <p class="mt-1 text-sm text-slate-500">
                            TEKKOM Talent Development & Matching Platform
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                placeholder="your.email@undip.ac.id"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                required
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100"
                                placeholder="********"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">
                                Role
                            </label>
                            <select
                                name="role"
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100">
                                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="lecturer" {{ old('role') === 'lecturer' ? 'selected' : '' }}>Author / Vendor</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <label class="inline-flex items-center text-sm text-slate-600">
                                <input type="checkbox" name="remember" class="mr-2 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                Remember me
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}"
                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-violet-600 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-200 transition hover:scale-[1.01] hover:opacity-95">
                            Sign In
                        </button>

                        <div class="text-center text-sm text-slate-500">
                            Don't have an account?
                            <a href="{{ route('register') }}"
                               class="font-semibold text-indigo-600 hover:text-indigo-700">
                                Register now
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-guest-layout>
