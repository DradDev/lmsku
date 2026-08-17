<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-8">

        <div class="w-full max-w-lg bg-white rounded-xl shadow-lg p-8"
             x-data="{ 
                 role: '{{ old('role', 'student') }}',
                 vendorType: '{{ old('vendor_type', 'company') }}',
                 institutionMode: '{{ old('institution_mode', $institutions->isNotEmpty() ? 'existing' : 'new') }}',
                 newInstName: '{{ old('new_institution_name', '') }}',
                 newInstCode: '{{ old('new_institution_code', '') }}',
                 generateCode() {
                     let clean = this.newInstName.replace(/^(pt\.?|cv\.?|ud\.?|yayasan|firma)\s+/i, '')
                                                 .replace(/\s+(tbk\.?|inc\.?|corp\.?|ltd\.?)$/i, '')
                                                 .replace(/[^a-zA-Z0-9\s]/g, '')
                                                 .trim();
                     let words = clean.split(/\s+/).filter(w => w.length > 0);
                     if (words.length === 0) { 
                         this.newInstCode = ''; 
                         return; 
                     }
                     if (words.length === 1) { 
                         this.newInstCode = words[0].substring(0, 4).toUpperCase(); 
                     } else if (words.length === 2) { 
                         this.newInstCode = (words[0].substring(0, 2) + words[1].substring(0, 2)).toUpperCase(); 
                     } else { 
                         this.newInstCode = words.slice(0, 4).map(w => w[0]).join('').toUpperCase(); 
                     }
                 }
             }">

            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
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
                        Nama Lengkap
                    </label>

                    <input type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        placeholder="Nama lengkap Anda">
                </div>

                <!-- EMAIL -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Alamat Email
                    </label>

                    <input type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        placeholder="email@domain.com">
                </div>

                <!-- ROLE -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Peran (Role Pendaftaran)
                    </label>

                    <select name="role"
                        x-model="role"
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium">
                        <option value="student">Student (Mahasiswa)</option>
                        <option value="lecturer">Lecturer (Dosen Akademik)</option>
                        <option value="vendor">Author (Mitra Vendor Industri)</option>
                    </select>
                </div>

                <!-- PEMINATAN (Khusus Student) -->
                <div class="mt-4" x-show="role === 'student'" x-transition>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Peminatan / Minat Bidang Awal <span class="text-xs text-indigo-600 font-semibold">(Pilih 1 atau lebih)</span>
                    </label>

                    <div class="space-y-2 max-h-48 overflow-y-auto p-3 border border-gray-300 rounded-lg bg-gray-50/80 shadow-inner">
                        @foreach ($skills as $skill)
                            <label class="flex items-center space-x-3 p-2 bg-white rounded-lg border border-gray-200 hover:border-indigo-400 cursor-pointer transition-all shadow-sm">
                                <input type="checkbox"
                                    name="peminatan[]"
                                    value="{{ $skill->name }}"
                                    class="rounded text-indigo-600 focus:ring-indigo-500 h-4 w-4 border-gray-300"
                                    {{ (is_array(old('peminatan')) && in_array($skill->name, old('peminatan'))) || old('peminatan') === $skill->name ? 'checked' : '' }}>
                                <span class="text-xs font-semibold text-gray-800">{{ $skill->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <p class="mt-1 text-xs text-gray-500">
                        Catatan preferensi awal (Opsional). Portofolio keahlian riil akan terbentuk otomatis dari aktivitas Course & Project.
                    </p>
                </div>

                <!-- SECTION KHUSUS VENDOR / MITRA INDUSTRI -->
                <div class="mt-4 p-4 border border-gray-200 bg-gray-50/50 rounded-xl space-y-4" x-show="role === 'vendor'" x-transition>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                            Jenis Mitra Industri:
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center p-3 bg-white border rounded-lg cursor-pointer transition-all shadow-sm text-xs font-semibold"
                                   :class="vendorType === 'company' ? 'border-indigo-600 bg-indigo-50 text-indigo-900 ring-1 ring-indigo-500' : 'border-gray-200 text-gray-700 hover:border-indigo-300'">
                                <input type="radio" name="vendor_type" value="company" x-model="vendorType" class="hidden">
                                <span>Badan Usaha (PT / CV / Lembaga)</span>
                            </label>

                            <label class="flex items-center justify-center p-3 bg-white border rounded-lg cursor-pointer transition-all shadow-sm text-xs font-semibold"
                                   :class="vendorType === 'individual' ? 'border-indigo-600 bg-indigo-50 text-indigo-900 ring-1 ring-indigo-500' : 'border-gray-200 text-gray-700 hover:border-indigo-300'">
                                <input type="radio" name="vendor_type" value="individual" x-model="vendorType" class="hidden">
                                <span>Perorangan / Independen</span>
                            </label>
                        </div>
                    </div>

                    <!-- JIKA BADAN USAHA (PT/CV) -->
                    <div x-show="vendorType === 'company'" class="space-y-3 pt-1" x-transition>
                        @if($institutions->isNotEmpty())
                            <div class="flex items-center gap-4 text-xs font-semibold text-gray-700 mb-1">
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="institution_mode" value="existing" x-model="institutionMode" class="text-indigo-600 focus:ring-indigo-500">
                                    <span>Pilih Perusahaan Terdaftar</span>
                                </label>
                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                    <input type="radio" name="institution_mode" value="new" x-model="institutionMode" class="text-indigo-600 focus:ring-indigo-500">
                                    <span>+ Daftarkan Perusahaan Baru</span>
                                </label>
                            </div>

                            <!-- Dropdown Existing -->
                            <div x-show="institutionMode === 'existing'" x-transition>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Nama Perusahaan / Lembaga:
                                </label>
                                <select name="institution_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <option value="">-- Pilih Perusahaan Mitra --</option>
                                    @foreach ($institutions as $inst)
                                        <option value="{{ $inst->id }}" {{ old('institution_id') == $inst->id ? 'selected' : '' }}>
                                            {{ $inst->name }} ({{ $inst->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="institution_mode" value="new">
                        @endif

                        <!-- Input New Company -->
                        <div x-show="institutionMode === 'new' || {{ $institutions->isEmpty() ? 'true' : 'false' }}" class="space-y-3" x-transition>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Nama Resmi Perusahaan / PT / CV:
                                </label>
                                <input type="text"
                                    name="new_institution_name"
                                    x-model="newInstName"
                                    @input="generateCode()"
                                    value="{{ old('new_institution_name') }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    placeholder="Contoh: PT Telkom Indonesia">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                    Kode Singkatan Institusi (3-5 Karakter):
                                </label>
                                <input type="text"
                                    name="new_institution_code"
                                    x-model="newInstCode"
                                    value="{{ old('new_institution_code') }}"
                                    maxlength="10"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm uppercase font-mono font-bold tracking-wider"
                                    placeholder="Contoh: TLKM">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Kata Sandi (Password)
                    </label>

                    <input type="password"
                        name="password"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        placeholder="********">
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Konfirmasi Kata Sandi
                    </label>

                    <input type="password"
                        name="password_confirmation"
                        required
                        class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                        placeholder="********">
                </div>

                <!-- REGISTER BUTTON -->
                <button
                    type="submit"
                    class="mt-6 w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 rounded-lg hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-md transition-all duration-200 text-sm">
                    Daftar Sekarang
                </button>

                <div class="text-center mt-4 text-sm text-gray-600">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="text-indigo-600 font-semibold hover:underline">
                        Masuk di sini
                    </a>
                </div>

            </form>

        </div>

    </div>

</x-guest-layout>