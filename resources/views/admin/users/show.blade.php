<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Users
                </a>

                <h1 class="text-3xl font-bold text-slate-900">User Detail</h1>
                <p class="text-slate-500 mt-2">View account information.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500 mb-1">Name</p>
                        <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500 mb-1">Email</p>
                        <p class="font-semibold text-slate-900">{{ $user->email }}</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500 mb-1">Role</p>
                        <p class="font-semibold text-slate-900">{{ ucfirst($user->role) }}</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4">
                        <p class="text-sm text-slate-500 mb-1">Created At</p>
                        <p class="font-semibold text-slate-900">
                            {{ optional($user->created_at)->format('d M Y, H:i') ?? '-' }}
                        </p>
                    </div>

                    @if($user->role === 'vendor')
                        <div class="rounded-xl bg-purple-50 border border-purple-200 p-4 md:col-span-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-purple-700 mb-2">Profil Kemitraan Vendor</p>
                            @if($user->institution)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900">🏢 {{ $user->institution->name }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">Tipe: Badan Usaha (PT / CV / Lembaga)</p>
                                    </div>
                                    <span class="px-3 py-1 bg-purple-600 text-white rounded-lg text-xs font-mono font-bold">
                                        Kode: {{ $user->institution->code }}
                                    </span>
                                </div>
                            @elseif($user->institution_type === 'individual')
                                <div>
                                    <p class="text-sm font-bold text-slate-900">👤 Praktisi Perorangan / Independen</p>
                                    <p class="text-xs text-slate-500 mt-0.5">Kredensial sertifikat: Format IND (Independent Mentor)</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                       class="rounded-xl bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700">
                        Edit User
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                       class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
