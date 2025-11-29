@props([
    'label' => 'Deskripsi',
    'id' => 'content',
    'name' => 'content',
    'value' => '',
])
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">

    <!-- JS -->
    {{-- <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script> --}}
    <style>
        trix-editor {
            min-height: 200px;
        }

        .dark trix-editor {
            background-color: #1f2937;
            /* dark:bg-boxdark */
            color: #f9fafb;
            /* text-white */
            border-color: #374151;
            /* dark:border-strokedark */
        }

        /* DARK MODE – Warna toolbar Trix */
        .dark .trix-button-group {
            background-color: #bfbfbf !important;
            /* bg-gray-800 */
            border-color: #fafafa !important;
            /* border-gray-700 */
            color: #f9fafb !important;
        }

        .dark .trix-button {
            color: #f9fafb !important;
            /* text-white */
        }

        .dark .trix-button:not([disabled]):hover {
            background-color: #797979 !important;
            /* hover:bg-gray-700 */
        }

        /* Untuk ikon bold/italic/link dsb */
        .dark .trix-button svg {
            fill: #f9fafb !important;
        }

        /* Dropdown link, attach file, dsb */
        .dark .trix-dialog {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
            border-color: #374151 !important;
        }

        .dark .trix-input--dialog {
            background-color: #111827 !important;
            /* bg-gray-900 */
            color: #f9fafb !important;
            border-color: #4b5563 !important;
            /* border-gray-600 */
        }
    </style>
@endpush
<div class="mb-5.5">
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">
        {{ $label }}
    </label>

    <input id="{{ $id }}" type="hidden" name="{{ $name }}" value="{{ $value }}">

    <trix-editor input="{{ $id }}"
        class="trix-content border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200">
    </trix-editor>
</div>
{{-- contoh

<x-form.trix label="Tugas & Fungsi" id="tugas_fungsi" name="tugas_fungsi"
                                :value="$profile->tugas_fungsi ?? ''" />
                                --}}
