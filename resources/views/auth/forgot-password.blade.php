<x-guest-layout>
    <div class="w-full max-w-5xl mx-auto py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

            {{-- LEFT BRANDING COLUMN --}}
            <div class="hidden lg:block">
                <div class="max-w-lg">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-indigo-600 mb-4">
                        Computer Engineering • UNDIP
                    </p>

                    <h1 class="text-5xl font-bold leading-tight text-slate-900">
                        Reset Password
                    </h1>

                    <p class="mt-5 text-lg leading-8 text-slate-600">
                        Forgot your password? No problem. Simply enter your registered email address and we will send you a password reset link to create a new one.
                    </p>

                    <div class="mt-8 rounded-2xl border border-white/60 bg-white/70 p-5 shadow-sm backdrop-blur space-y-2">
                        <div class="flex items-center gap-2 text-indigo-600 font-bold text-sm">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Secure Password Recovery</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Your account security is our top priority. The password reset link will expire in 60 minutes.
                        </p>
                    </div>
                </div>
            </div>

            {{-- RIGHT FORM CARD --}}
            <div class="w-full max-w-md mx-auto">
                <div class="rounded-3xl border border-white/70 bg-white/85 p-8 shadow-2xl shadow-slate-200/70 backdrop-blur">
                    <div class="text-center mb-6">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg mb-4">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </div>

                        <h2 class="text-2xl font-bold text-slate-900">
                            Forgot Password?
                        </h2>

                        <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                            Enter your account email address below and we'll send you instructions to reset your password.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-xs font-semibold text-emerald-700 shadow-sm flex items-center gap-2">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Registered Email Address
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100 transition shadow-sm"
                                placeholder="name@undip.ac.id"
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200 transition"
                        >
                            Email Password Reset Link →
                        </button>

                        <div class="pt-4 text-center border-t border-slate-100">
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                                <span>Back to Login</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
