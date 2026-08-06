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
                        Create New Password
                    </h1>

                    <p class="mt-5 text-lg leading-8 text-slate-600">
                        Please set your new password below. Make sure your password is strong and easy for you to remember.
                    </p>

                    <div class="mt-8 rounded-2xl border border-white/60 bg-white/70 p-5 shadow-sm backdrop-blur space-y-2">
                        <div class="flex items-center gap-2 text-indigo-600 font-bold text-sm">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            <span>Security Recommendation</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            Use at least 8 characters with a mix of letters, numbers, and special symbols.
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
                                <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                            </svg>
                        </div>

                        <h2 class="text-2xl font-bold text-slate-900">
                            Set New Password
                        </h2>

                        <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                            Type your new account password to complete the reset process.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs text-rose-700">
                            <ul class="list-disc pl-4 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div>
                            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Email Address
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                required
                                autofocus
                                readonly
                                class="w-full rounded-2xl border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-700 cursor-not-allowed"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700">
                                New Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                required
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100 transition shadow-sm"
                                placeholder="********"
                            >
                        </div>

                        <div>
                            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-700">
                                Confirm New Password
                            </label>
                            <input
                                type="password"
                                name="password_confirmation"
                                required
                                class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-100 transition shadow-sm"
                                placeholder="********"
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200 transition"
                        >
                            Reset Password Now →
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
