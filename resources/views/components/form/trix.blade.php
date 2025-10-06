@props([
    'label' => 'Deskripsi',
    'id' => 'content',
    'name' => 'content',
    'value' => '',
])
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.0/dist/trix.css">

    <!-- JS -->
    <script src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
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
