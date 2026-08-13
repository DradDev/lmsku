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
                    COMPRO TEKKOM
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

                        <option value="student">Student (Mahasiswa)</option>
                        <option value="lecturer">Lecturer (Dosen Akademik)</option>
                        <option value="vendor">Author (Mitra Vendor Industri)</option>

                    </select>

                </div>


                <!-- PEMINATAN (Multi-Choice Choice untuk Student) -->

                <div class="mt-4" x-show="role === 'student'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Specialization / Interest <span class="text-xs text-indigo-600 font-semibold">(Choose 1 or more)</span>
                    </label>

                    <div class="space-y-2 max-h-52 overflow-y-auto p-3 border border-gray-300 rounded-lg bg-gray-50/80 shadow-inner">
                        @foreach ($skills as $skill)
                            <label class="flex items-center space-x-3 p-2.5 bg-white rounded-lg border border-gray-200 hover:border-indigo-400 cursor-pointer transition-all shadow-sm">
                                <input type="checkbox"
                                    name="peminatan[]"
                                    value="{{ $skill->name }}"
                                    class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4 border-gray-300"
                                    {{ (is_array(old('peminatan')) && in_array($skill->name, old('peminatan'))) || old('peminatan') === $skill->name ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-800">{{ $skill->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <p class="mt-1.5 text-xs text-gray-500">
                        Preferensi bidang awal (Opsional). Skill kompetensi riil Anda akan otomatis terbentuk dari Course yang Anda ikuti.
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