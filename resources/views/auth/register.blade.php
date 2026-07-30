<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100">

        <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8" x-data="{ role: '{{ old('role', 'student') }}' }">

            <div class="text-center mb-6">

                <div class="w-16 h-16 mx-auto bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-2xl">
                    🎓
                </div>

                <h1 class="text-2xl font-bold mt-4">
                    Diponegoro University
                </h1>

                <p class="text-gray-500 text-sm">
                    Computer Engineering LMS
                </p>

            </div>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- NAME -->

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Name
                    </label>

                    <input type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Your name">

                </div>


                <!-- EMAIL -->

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="your.email@undip.ac.id">

                </div>


                <!-- ROLE -->

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Role
                    </label>

                    <select name="role"
                        x-model="role"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">

                        <option value="student">Student</option>
                        <option value="lecturer">Lecturer</option>

                    </select>

                </div>


                <!-- PEMINATAN (hanya untuk Student) -->

                <div class="mt-4" x-show="role === 'student'" x-transition>
                    <label class="block text-sm font-medium text-gray-700">
                        Peminatan <span class="text-gray-400">(Skill Utama)</span>
                    </label>

                    <select name="peminatan"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        x-bind:required="role === 'student'">

                        <option value="">-- Pilih Peminatan --</option>
                        @foreach ($skills as $skill)
                            <option value="{{ $skill->name }}" {{ old('peminatan') === $skill->name ? 'selected' : '' }}>
                                {{ $skill->name }}
                            </option>
                        @endforeach

                    </select>

                    <p class="mt-1 text-xs text-gray-400">
                        Pilih bidang keahlian utama yang ingin Anda fokuskan.
                    </p>

                </div>


                <!-- PASSWORD -->

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Password
                    </label>

                    <input type="password"
                        name="password"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="********">

                </div>


                <!-- CONFIRM PASSWORD -->

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Confirm Password
                    </label>

                    <input type="password"
                        name="password_confirmation"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="********">

                </div>


                <!-- REGISTER BUTTON -->

                <button
                    type="submit"
                    class="mt-6 w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white py-2 rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-200">

                    Register

                </button>


                <div class="text-center mt-4 text-sm">

                    Already have an account?

                    <a href="{{ route('login') }}"
                        class="text-indigo-600 hover:underline">

                        Login here

                    </a>

                </div>

            </form>

        </div>

    </div>

</x-guest-layout>