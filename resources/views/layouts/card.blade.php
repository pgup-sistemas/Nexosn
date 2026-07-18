<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">

    <title>@yield('title', 'Cartão Digital') | Card</title>
    <meta name="description" content="@yield('description', 'Cartão de visita digital.')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('title', 'Cartão Digital')">
    <meta property="og:description" content="@yield('description', 'Cartão de visita digital.')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:type" content="profile">
    <meta property="og:url" content="{{ url()->current() }}">

    @yield('card_colors')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
</head>
<body class="min-h-screen" style="background-color: #f3f4f6; font-family: 'Inter', sans-serif;">

    <main class="mx-auto max-w-[400px] min-h-screen bg-white shadow-lg">
        @yield('content')
    </main>

</body>
</html>
