<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: { primary: '#1E40AF' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>

    <!-- Inter Font (preload) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    <title>Credify Bank – @yield('title', 'Secure Online Banking')</title>

    <!-- Preloader styles (FIXED - only hide body overflow while preloader is active) -->
    <style>
        body.preloader-active {
            overflow: hidden;
        }
        #preloader {
            transition: opacity 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white min-h-screen flex flex-col preloader-active">

    <!-- PRELOADER (optional - uncomment if needed) -->
    <div id="preloader"
         class="fixed inset-0 bg-white dark:bg-gray-900 flex items-center justify-center z-50">
        <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
    </div>

    <!-- HEADER -->
    @include('snippets.header')

    <!-- MAIN CONTENT -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- FOOTER -->
    @include('snippets.footer')

    <!-- GO TO TOP -->
    <button id="goTop"
            class="fixed bottom-6 right-6 p-3 bg-primary hover:bg-primary/90 text-white rounded-full shadow-lg
                   opacity-0 invisible transition-all duration-300 transform translate-y-4 z-40">
        <i class="ti ti-arrow-up text-xl"></i>
    </button>

    <!-- SCRIPTS -->
    <script>
        // Remove preloader and restore scrolling
        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            document.body.classList.remove('preloader-active');
            
            if (preloader) {
                preloader.style.opacity = '0';
                setTimeout(() => preloader.remove(), 300);
            }
        });

        // Fallback: ensure scrolling is enabled after 2 seconds max
        setTimeout(() => {
            document.body.classList.remove('preloader-active');
        }, 2000);

        // Go-to-top
        const goTop = document.getElementById('goTop');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                goTop.classList.remove('opacity-0', 'invisible', 'translate-y-4');
                goTop.classList.add('opacity-100', 'visible', 'translate-y-0');
            } else {
                goTop.classList.remove('opacity-100', 'visible', 'translate-y-0');
                goTop.classList.add('opacity-0', 'invisible', 'translate-y-4');
            }
        });
        goTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

        // Theme toggle
        const themeBtn = document.getElementById('themeToggle');
        const html = document.documentElement;
        if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        }
        themeBtn?.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
        });
    </script>
</body>
</html>