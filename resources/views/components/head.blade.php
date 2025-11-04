<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard - Bapperida Merauke' }}</title>

    <!-- Tailwind -->
    <script>
        // Pastikan dark mode aktif secepat mungkin sebelum render
        if (
            localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('tailadmin/build/favicon.ico') }}">

    <!-- Custom Style -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Hapus scrollbar tertentu */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Scrollbar global */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        .dark ::-webkit-scrollbar-thumb {
            background-color: #475569;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background-color: #64748b;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(100px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes progress {
            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }

        .animate-progress {
            animation: progress var(--toast-duration, 4s) linear forwards;
        }
    </style>

    @stack('styles')
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
