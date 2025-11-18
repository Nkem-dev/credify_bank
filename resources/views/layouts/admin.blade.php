<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credify Bank - @yield('title', 'Admin Dashboard')</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" />

    <!-- Tailwind Config (ONCE) -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',
                        secondary: '#64748B',
                        accent: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                        dark: '#1E293B',
                        light: '#F8FAFC',
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        };
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color .3s, color .3s; }
        .sidebar-link { @apply text-gray-700 dark:text-gray-300; }
        .sidebar-link:hover { @apply bg-blue-50 dark:bg-gray-700 text-primary; }
        .sidebar-active { @apply bg-primary text-white; }
        .sidebar-active:hover { @apply bg-primary/90; }
        .group:hover .group-hover\:text-primary { @apply text-primary; }
        .dropdown:hover .dropdown-menu { display: block; }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors">

    @include('partials.admin.sidebar')

    <div class="lg:ml-64 min-h-screen flex flex-col">
        @include('partials.admin.header')
        <main class="flex-1 p-4">@yield('content')</main>
        @include('partials.admin.footer')
    </div>

    <!-- GLOBAL THEME TOGGLE SCRIPT (RUNS ON EVERY PAGE AUTOMATICALLY) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;
            const toggle = document.getElementById('themeToggle');

            // === 1. APPLY SAVED THEME ON EVERY PAGE LOAD ===
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            // === 2. TOGGLE & SAVE THEME ===
            toggle?.addEventListener('click', () => {
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });
        });
    </script>

    <!-- Page-specific scripts -->
    @stack('scripts')

     <!-- iziToast JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    @if (session('success') || session('error') || session('info') || session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                iziToast.success({
                    title: 'Success',
                    message: @json(session('success')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    animateInside: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('error'))
                iziToast.error({
                    title: 'Error',
                    message: @json(session('error')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    animateInside: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('info'))
                iziToast.info({
                    title: 'Info',
                    message: @json(session('info')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    animateInside: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @elseif (session('warning'))
                iziToast.warning({
                    title: 'Warning',
                    message: @json(session('warning')),
                    position: 'topRight',
                    timeout: 5000,
                    pauseOnHover: true,
                    progressBar: true,
                    animateInside: true,
                    transitionIn: 'fadeInLeft',
                    transitionOut: 'fadeOutRight'
                });
            @endif
        });
    </script>
    @endif
</body>
</html>