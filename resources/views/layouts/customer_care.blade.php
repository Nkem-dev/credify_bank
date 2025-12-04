

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credify Bank - @yield('title', 'Customer Care Dashboard')</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" />

    <!-- Tailwind Config -->
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

    <style>
        body { font-family: 'Inter', sans-serif; transition: background-color .3s, color .3s; }
        .sidebar-link { @apply text-gray-700 dark:text-gray-300; }
        .sidebar-link:hover { @apply bg-blue-50 dark:bg-gray-700 text-primary; }
        .sidebar-active { @apply bg-primary text-white; }
        .sidebar-active:hover { @apply bg-primary/90; }
        .dropdown:hover .dropdown-menu { display: block; }
        .fancy-scrollbar::-webkit-scrollbar { width: 6px; }
        .fancy-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 6px; }
        .fancy-scrollbar::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 6px; }
        .fancy-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        .dark .fancy-scrollbar::-webkit-scrollbar-track { background: #1f2937; }
        .dark .fancy-scrollbar::-webkit-scrollbar-thumb { background: #64748b; }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors">

    @include('partials.customer_care.sidebar')

    <div class="lg:ml-64 min-h-screen flex flex-col">
        @include('partials.customer_care.header')
        <main class="flex-1 p-4">@yield('content')</main>
        @include('partials.customer_care.footer')
    </div>

    <!-- GLOBAL THEME TOGGLE SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;
            const toggle = document.getElementById('themeToggle');

            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            toggle?.addEventListener('click', () => {
                const isDark = html.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
            });

            // Sidebar toggle for mobile
            const sidebar = document.getElementById('sidebar');
            const openBtn = document.getElementById('openSidebar');
            
            openBtn?.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        });
    </script>

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