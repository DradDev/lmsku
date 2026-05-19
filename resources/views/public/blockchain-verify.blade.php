<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Validasi Blockchain Certificate</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen">
        <div class="max-w-5xl mx-auto px-6 py-10">

            <div class="mb-8 flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">
                        Public Verification
                    </p>

                    <h1 class="mt-2 text-3xl font-bold text-slate-900">
                        Validasi Hash Blockchain
                    </h1>

                    <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                        Masukkan hash blockchain atau certificate hash untuk mengecek keaslian certificate tanpa perlu login.
                    </p>
                </div>

                <a href="{{ url('/') }}"
                   class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Kembali
                </a>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <form action="{{ route('blockchain.verify.check') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Hash Blockchain / Certificate Hash
                        </label>

                        <textarea name="hash"
                                  rows="3"
                                  class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 focus:border-indigo-500 focus:outline-none"
                                  placeholder="Contoh: 0xabc123... atau hash certificate..."
                                  required>{{ old('hash', $hash) }}</textarea>

                        @error('hash')
                            <p class="mt-2 text-sm text-rose-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                        Validasi Hash
                    </button>
                </form>
            </div>

            @if ($status)
                <div class="mt-6 rounded-3xl border p-6 shadow-sm
                    @if ($status === 'valid')
                        border-emerald-200 bg-emerald-50
                    @elseif ($status === 'invalid')
                        border-rose-200 bg-rose-50
                    @else
                        border-amber-200 bg-amber-50
                    @endif">

                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl
                            @if ($status === 'valid')
                                bg-emerald-100 text-emerald-700
                            @elseif ($status === 'invalid')
                                bg-rose-100 text-rose-700
                            @else
                                bg-amber-100 text-amber-700
                            @endif">
                            @if ($status === 'valid')
                                ✓
                            @elseif ($status === 'invalid')
                                !
                            @else
                                ?
                            @endif
                        </div>

                        <div class="flex-1">
                            <h2 class="text-xl font-bold
                                @if ($status === 'valid')
                                    text-emerald-800
                                @elseif ($status === 'invalid')
                                    text-rose-800
                                @else
                                    text-amber-800
                                @endif">
                                @if ($status === 'valid')
                                    Certificate Valid
                                @elseif ($status === 'invalid')
                                    Certificate Tidak Valid
                                @else
                                    Validasi Tidak Bisa Diproses
                                @endif
                            </h2>

                            <p class="mt-1 text-sm
                                @if ($status === 'valid')
                                    text-emerald-700
                                @elseif ($status === 'invalid')
                                    text-rose-700
                                @else
                                    text-amber-700
                                @endif">
                                {{ $message }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($certificate)
                <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 mb-5">
                        Detail Certificate
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-slate-500">Nama Student</p>
                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $certificate->student_name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-slate-500">Email Student</p>
                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $certificate->student_email ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-slate-500">Course</p>
                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $certificate->course_name ?? '-' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                            <p class="text-slate-500">Tanggal Terbit</p>
                            <p class="mt-1 font-semibold text-slate-900">
                                {{ $certificate->issued_at ?? $certificate->created_at ?? '-' }}
                            </p>
                        </div>

                        @if (isset($certificate->certificate_number))
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4">
                                <p class="text-slate-500">Nomor Certificate</p>
                                <p class="mt-1 font-semibold text-slate-900">
                                    {{ $certificate->certificate_number }}
                                </p>
                            </div>
                        @endif

                        @if (isset($certificate->blockchain_hash))
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 md:col-span-2">
                                <p class="text-slate-500">Blockchain Hash</p>
                                <p class="mt-1 break-all font-mono text-xs text-slate-900">
                                    {{ $certificate->blockchain_hash }}
                                </p>
                            </div>
                        @endif

                        @if (isset($certificate->certificate_hash))
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 md:col-span-2">
                                <p class="text-slate-500">Certificate Hash</p>
                                <p class="mt-1 break-all font-mono text-xs text-slate-900">
                                    {{ $certificate->certificate_hash }}
                                </p>
                            </div>
                        @endif

                        @if (isset($certificate->hash))
                            <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 md:col-span-2">
                                <p class="text-slate-500">Hash</p>
                                <p class="mt-1 break-all font-mono text-xs text-slate-900">
                                    {{ $certificate->hash }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</body>
</html>