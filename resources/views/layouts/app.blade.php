<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <header class="bg-white shadow mb-8 sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 hover:text-blue-800 transition">
                Blog<span class="text-gray-700">Laravel</span>
            </a>
            </div>
    </header>

    <main class="container mx-auto px-4 min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white text-center py-6 mt-12">
        <p>&copy; {{ date('Y') }} Blog Laravel. Desenvolvido para teste.</p>
    </footer>

</body>
</html>