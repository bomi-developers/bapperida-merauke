<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Terjadi Kesalahan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white">

    <div class="text-center max-w-lg px-6">
        {{-- Kode Error --}}
        <h1
            class="text-8xl font-extrabold mb-4 bg-gradient-to-r from-red-400 to-pink-500 bg-clip-text text-transparent drop-shadow-lg">
            @yield('code', 'Error')
        </h1>

        {{-- Pesan --}}
        <h2 class="text-2xl font-semibold mb-6">@yield('message', 'Terjadi kesalahan yang tidak diketahui.')</h2>

        {{-- Tombol Aksi --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ url()->previous() }}" class="px-6 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition">←
                Kembali</a>
            <a href="{{ url('/') }}" class="px-6 py-2 bg-red-600 hover:bg-red-500 rounded-lg transition">🏠 Ke
                Beranda</a>
        </div>

        {{-- Footer kecil --}}
        <p class="text-sm text-gray-400 mt-10">
            &copy; {{ date('Y') }} — {{ config('app.name', 'BAPPERIDA Kab. Merauke') }}. Semua hak dilindungi.
        </p>
    </div>

</body>

</html>
