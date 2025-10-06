<div>
    @if ($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg
                                bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
                                focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 resize-none',
        ]) }}>{{ $slot }}</textarea>
</div>
