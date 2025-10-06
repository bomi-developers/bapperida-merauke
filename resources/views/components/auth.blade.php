<!DOCTYPE html>
<html lang="en">

<x-head></x-head>

<body class="bg-gradient-to-br from-blue-100 via-white to-blue-300 ">


    <!-- ===== Preloader End ===== -->
    {{-- <!-- ===== Maintenance Popup ===== -->
    <div x-show="showMaintenance" x-transition
        class="fixed inset-0 z-50 flex items-center justify-center  bg-black bg-opacity-30">
        <div
            class="bg-warning dark:bg-yellow-300 p-8 rounded-3xl shadow-2xl max-w-md text-center border-2 border-yellow-400">
            <h2 class="text-2xl font-bold text-red-700 mb-4">Website Maintenance</h2>
            <p class="mb-6 text-gray-800 dark:text-gray-900">
                Saat ini website sedang dalam perbaikan. Beberapa fitur mungkin tidak tersedia.
            </p>
            <button @click="showMaintenance = false"
                class="px-5 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                Close
            </button>
        </div>
    </div> --}}


    <!-- ===== Page Wrapper Start ===== -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- ===== Content Area Start ===== -->
        <div class="w-full max-w-6xl mx-auto">
            {{ $slot }}
        </div>
        <!-- ===== Content Area End ===== -->
    </div>
    <!-- ===== Page Wrapper End ===== -->
    {{-- <script defer src="{{ asset('tailadmin/build/bundle.js') }}"></script> --}}
</body>

</html>
