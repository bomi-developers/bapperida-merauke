<x-layout>
    <x-header />

    <!-- Container full height viewport -->
    <div class="flex flex-col h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 p-6">

        <div class="flex justify-between items-center mb-6 flex-shrink-0">

            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Landing Page Management</h2>

            <div class="flex gap-2">
                <button onclick="openCreateForm()"
                    class="px-4 py-2 bg-indigo-600 text-sm text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                    + Tambah Section
                </button>
                <button onclick="openOrderModal()"
                    class="px-4 py-2 dark:bg-slate-700 bg-gray-300 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 transition-colors duration-200 ">
                    <i class="bi bi-list-check text-xl"></i>
                </button>
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="px-4 py-2 bg-gray-300 text-xl dark:bg-slate-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-400 transition-colors duration-200">
                        <i class="bi bi-caret-down-fill"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.outside="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
                        <ul class="flex flex-col">
                            <li>
                                <a href="{{ url('/') }}" target="_blank"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="bi bi-box-arrow-up-right"></i> Page Preview
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.lending.template') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <i class="bi bi-code-slash"></i> Template Editor
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Iframe -->
        <div class="flex-1 overflow-auto">
            <iframe id="lendingIframe" src="/admin/template-preview/all"
                class="w-full h-full border-md rounded-lg border-indigo-700"></iframe>
        </div>

    </div>

    @include('pages.lending_page.modal')
    @include('pages.lending_page.script')
</x-layout>
