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
            <div class="mt-6 rounded-3xl border border-slate-200 bg-white p-8 shadow-sm space-y-6">

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            ⛓️ {{ $result->cert_type ?? 'Certified Credential' }}
                        </span>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900">
                            Detail Kredensial Blockchain
                        </h2>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-100 text-emerald-800 text-xs font-extrabold border border-emerald-200 self-start sm:self-auto">
                        <span>✓ Tamper-Proof Validated</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Nama Penerima (Student Talent)
                        </p>
                        <p class="mt-1 text-base font-bold text-slate-900">
                            {{ $result->student_name ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $result->student_email ?? '-' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Program / Project / Course
                        </p>
                        <p class="mt-1 text-base font-bold text-indigo-700">
                            {{ $result->title ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $result->category ?? 'Academic & Industry' }}</p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Penerbit / Penilai (Author / Vendor)
                        </p>
                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ $result->issuer ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            Waktu Verifikasi & Pencatatan
                        </p>
                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ optional(\Carbon\Carbon::parse($result->verified_at ?? now()))->format('d M Y, H:i') }} WIB
                        </p>
                        <p class="text-xs text-emerald-600 font-bold mt-0.5">Otentikasi oleh {{ $result->verified_by_name ?? 'Admin LP3M' }}</p>
                    </div>
                </div>

                {{-- Blockchain Technical Panel --}}
                <div class="rounded-2xl border border-indigo-200 bg-indigo-50/60 p-5 space-y-3 text-xs">
                    <div class="flex items-center justify-between border-b border-indigo-200/60 pb-2">
                        <span class="font-extrabold text-indigo-950 uppercase tracking-wider">
                            ⛓️ Bukti Otentisitas Kriptografis Blockchain
                        </span>
                        <span class="font-mono text-indigo-700 font-bold">
                            {{ $result->credential_code ?? '-' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <p class="font-bold text-slate-500 text-[11px]">Blockchain ID</p>
                            <p class="font-mono font-bold text-slate-800 mt-0.5">{{ $result->blockchain_id ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-slate-500 text-[11px]">Transaction ID (TxID)</p>
                            <p class="font-mono text-slate-700 mt-0.5 truncate" title="{{ $result->tx_id }}">{{ $result->tx_id ?? '-' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="font-bold text-slate-500 text-[11px]">Cryptographic SHA-256 Ledger Hash</p>
                        <p class="mt-1 font-mono text-[11px] font-bold text-indigo-900 bg-white p-3 rounded-xl border border-indigo-200 break-all select-all shadow-xs">
                            {{ $result->blockchain_hash ?? '-' }}
                        </p>
                    </div>
                </div>

            </div>
            @endif

        </div>
    </div>
</body>

</html>