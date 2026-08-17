<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'COMPRO TEKKOM') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="relative min-h-screen overflow-hidden bg-slate-100">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.12),_transparent_30%),radial-gradient(circle_at_bottom_right,_rgba(139,92,246,0.12),_transparent_30%)]"></div>

        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-blue-400/20 blur-3xl"></div>
        <div class="absolute top-1/3 -right-24 h-80 w-80 rounded-full bg-violet-400/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-sky-300/20 blur-3xl"></div>

        <div class="absolute inset-0 opacity-[0.035]"
             style="background-image: linear-gradient(to right, #0f172a 1px, transparent 1px), linear-gradient(to bottom, #0f172a 1px, transparent 1px); background-size: 36px 36px;">
        </div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-10">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
