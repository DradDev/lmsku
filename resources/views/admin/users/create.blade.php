<x-app-layout>
    <div class="min-h-screen bg-slate-50">
        <div class="max-w-3xl mx-auto px-6 py-8">
            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 mb-4">
                    ← Back to Users
                </a>

                <h1 class="text-3xl font-bold text-slate-900">Create User</h1>
                <p class="text-slate-500 mt-2">Add a new admin, lecturer, or student account.</p>
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
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700 mb-2">Role</label>
                        <select name="role" id="role"
                                class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                            <option value="">Select Role</option>
                            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                            <option value="lecturer" @selected(old('role') === 'lecturer')>Lecturer</option>
                            <option value="student" @selected(old('role') === 'student')>Student</option>
                        </select>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" id="password"
                               class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                               required>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="submit"
                                class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-medium text-white hover:bg-slate-800">
                            Create User
                        </button>

                        <a href="{{ route('admin.users.index') }}"
                           class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
