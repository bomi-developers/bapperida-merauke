<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BAPPERIDA Merauke</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        /* Transisi halus untuk dropdown */
        .group .dropdown {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
            pointer-events: none;
        }

        .group:hover .dropdown {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        /* Kelas untuk Animasi Scroll - PENYEMPURNAAN */
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

        /* Menerapkan animasi ke navbar */
        #main-nav {
            animation: slide-down 0.7s ease-in-out;
        }

        /* Mendefinisikan animasi bernama "fadeInUp" */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
                /* Mulai dari 30px di bawah posisi asli */
            }

            to {
                opacity: 1;
                transform: translateY(0);
                /* Kembali ke posisi asli */
            }
        }

        /* Kelas untuk menerapkan animasi */
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }
    </style>
    @stack('styles')
</head>
