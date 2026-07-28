<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Validasi Hash Blockchain</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen">
        <div class="mx-auto max-w-5xl px-6 py-10">

            {{-- Header --}}
            <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600">
                        Public Verification
                    </p>

                    <h1 class="mt-2 text-3xl font-bold text-slate-900">
                        Validasi Hash Blockchain
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm text-slate-500">
                        Masukkan hash blockchain untuk mengecek data hasil quiz
                        tanpa perlu login.
                    </p>
                </div>

                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Kembali
                </a>

            </div>

            {{-- Form --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <form action="{{ route('blockchain.verify.check') }}"
                    method="POST"
                    class="space-y-5">

                    @csrf

                    <div>
                        <label for="hash"
                            class="mb-2 block text-sm font-semibold text-slate-700">
                            Hash Blockchain
                        </label>

                        <textarea
                            id="hash"
                            name="hash"
                            rows="4"
                            required
                            placeholder="Masukkan hash blockchain..."
                            class="w-full rounded-2xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-900 transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">{{ old('hash', $hash ?? '') }}</textarea>

                        @error('hash')
                        <p class="mt-2 text-sm text-rose-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        Validasi Hash
                    </button>

                </form>
            </div>

            {{-- Status --}}
            @if ($status)
            @php
            $statusConfig = match ($status) {
            'valid' => [
            'border' => 'border-emerald-200',
            'bg' => 'bg-emerald-50',
            'iconBg' => 'bg-emerald-100',
            'iconText' => 'text-emerald-700',
            'titleText' => 'text-emerald-800',
            'descText' => 'text-emerald-700',
            'icon' => '✓',
            'title' => 'Hash Valid',
            ],

            'invalid' => [
            'border' => 'border-rose-200',
            'bg' => 'bg-rose-50',
            'iconBg' => 'bg-rose-100',
            'iconText' => 'text-rose-700',
            'titleText' => 'text-rose-800',
            'descText' => 'text-rose-700',
            'icon' => '!',
            'title' => 'Hash Tidak Valid',
            ],

            default => [
            'border' => 'border-amber-200',
            'bg' => 'bg-amber-50',
            'iconBg' => 'bg-amber-100',
            'iconText' => 'text-amber-700',
            'titleText' => 'text-amber-800',
            'descText' => 'text-amber-700',
            'icon' => '?',
            'title' => 'Validasi Tidak Bisa Diproses',
            ],
            };
            @endphp

            <div class="mt-6 rounded-3xl border p-6 shadow-sm
                    {{ $statusConfig['border'] }}
                    {{ $statusConfig['bg'] }}">

                <div class="flex items-start gap-4">

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-bold
                            {{ $statusConfig['iconBg'] }}
                            {{ $statusConfig['iconText'] }}">
                        {{ $statusConfig['icon'] }}
                    </div>

                    <div class="flex-1">

                        <h2 class="text-xl font-bold {{ $statusConfig['titleText'] }}">
                            {{ $statusConfig['title'] }}
                        </h2>

                        <p class="mt-1 text-sm {{ $statusConfig['descText'] }}">
                            {{ $message }}
                        </p>

                    </div>
                </div>
            </div>
            @endif

            {{-- Result --}}
            @if ($result)
            <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="mb-5 text-xl font-bold text-slate-900">
                    Detail Validasi
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">
                            Nama Student
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $result->student_name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm text-slate-500">
                            Judul Quiz
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $result->quiz_title ?? '-' }}
                        </p>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>
</body>

</html>