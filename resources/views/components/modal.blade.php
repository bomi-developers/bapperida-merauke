<!-- resources/views/components/modal.blade.php -->
<div id="{{ $id ?? 'modalTemplate' }}"
    class="fixed inset-0 hidden bg-black/10 backdrop-blur-sm flex items-center justify-center z-50 transition-all duration-300">
    <div
        class="relative w-full max-w-lg bg-white dark:bg-gray-900 rounded-2xl shadow-xl p-6 border border-gray-200 dark:border-gray-700">

        <!-- Header -->
        @if (!empty($title))
            <h3 class="text-xl font-semibold mb-5 text-gray-900 dark:text-white" id="modalTitle">
                {{ $title }}
            </h3>
            <button onclick="closeForm()"
                class="absolute top-4 right-4 text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
        @endif

        <!-- Content -->
        <div class="modal-content">
            {{ $slot }}
        </div>

        <!-- Footer (optional) -->
        @if (!empty($footer))
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
