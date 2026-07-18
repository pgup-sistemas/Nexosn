<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script>document.addEventListener('DOMContentLoaded', () => { if (window.lucide) lucide.createIcons(); });</script>
</head>
<body class="min-h-screen flex flex-col items-center justify-center antialiased"
      style="background-color: var(--color-primary, #003049); font-family: 'Inter', sans-serif;">

    <div class="w-full max-w-md px-4">
        <div class="text-center mb-8">
            <a href="/" wire:navigate class="text-white text-3xl font-semibold tracking-tight">Card</a>
            <p class="text-white/60 text-sm mt-1">Seu cartão digital profissional</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
