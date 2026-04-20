<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">User Management</h1>
                    <p class="text-slate-500 mt-2">
                        Manage admin, lecturer, and student accounts.
                    </p>
                </div>

                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-800">
                    + Add User
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                @if($users->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Role</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Created</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $roleClass = match($user->role) {
                                                    'admin' => 'bg-red-100 text-red-700',
                                                    'lecturer' => 'bg-blue-100 text-blue-700',
                                                    default => 'bg-green-100 text-green-700',
                                                };
                                            @endphp
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $roleClass }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500">
                                            {{ optional($user->created_at)->format('d M Y') ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.users.show', $user->id) }}"
                                                   class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200">
                                                    View
                                                </a>

                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                   class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                                                    Edit
                                                </a>

                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-slate-500">
                        Belum ada user.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
