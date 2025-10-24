@props([
    'icon' => 'bi-eye-fill',
    'value' => '$0',
    'colot' => 'indigo-600',
    'title' => 'Total',
    'change' => '0%',
    'changeColor' => 'green',
])

<div class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-200 dark:border-transparent">
    <div class="flex items-center justify-between">
        <div class="text-2xl text-{{ $color ?? 'indigo-600' }}">
            <i class="bi {{ $icon }} text-{{ $color ?? 'indigo-600' }}"></i>
        </div>
    </div>
    <h3 class="text-3xl font-bold mt-4 text-gray-900 dark:text-white">{{ $value }}</h3>
    <div class="flex items-center justify-between text-sm mt-1">
        <span class="text-gray-500 dark:text-slate-400">{{ $title }}</span>
        @if ($change)
            <div class="text-xs text-{{ $change > 0 ? 'green' : 'red' }}-500">
                {{ $change > 0 ? '+' : '' }}{{ $change }}%
            </div>
        @endif
    </div>
</div>
