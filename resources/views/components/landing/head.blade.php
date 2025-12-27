<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/LogoKabMerauke.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/LogoKabMerauke.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/LogoKabMerauke.png') }}">

    {{-- SEO Dynamic --}}
    <title>@yield('meta_title', 'BAPPERIDA Kabupaten Merauke')</title>
    <meta name="description" content="@yield('meta_description', 'BAPPERIDA kabupaten merauke bersama rakyat')">
    <meta name="keywords" content="@yield('meta_keywords', 'BAPPERIDA,Merauke, Kabupaten merauke, kota rusa, papua selatan, papua, adata papua, indonesia, bapperida dan masyarakat, Bapperida merauke, bapperida papua selatan, bapperida indonesia, merauke, merauke cerdas, merauke membangun')">

    {{-- Open Graph (Facebook, WhatsApp) --}}
    <meta property="og:title" content="@yield('meta_title', 'BAPPERIDA Kabupaten Merauke')">
    <meta property="og:description" content="@yield('meta_description', 'BAPPERIDA Kabupaten Merauke')">
    <meta property="og:type" content="article">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    {{-- gambar  --}}
    <meta property="og:image" content="@yield('meta_image', asset('assets/LogoKabMerauke.png'))">


    {{-- Twitter Card --}}
    <meta name="twitter:title" content="@yield('meta_title', 'BAPPERIDA Kabupaten Merauke')">
    <meta name="twitter:description" content="@yield('meta_description', 'BAPPERIDA Kabupaten Merauke')">
    <meta name="twitter:image" content="@yield('meta_image', asset('assets/LogoKabMerauke.png'))">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <style>
        .reveal-on-scroll {
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        /* Kondisi awal sebelum terlihat: transparan dan sedikit di bawah */
        .reveal-on-scroll:not(.is-visible) {
            opacity: 0;
            transform: translateY(50px);
        }

        /* Kondisi akhir setelah terlihat: normal */
        .reveal-on-scroll.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes slide-down {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }


        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);

            }

            to {
                opacity: 1;
                transform: translateY(0);

            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }
    </style>
    @stack('styles')
</head>
