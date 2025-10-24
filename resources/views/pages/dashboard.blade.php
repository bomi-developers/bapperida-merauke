<x-layout>
    <x-header></x-header>
    <!-- ===== Main Content Start ===== -->
    <!-- Main section -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 dark:bg-slate-900 p-6">
        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <x-card.card1 icon="bi-eye-fill" value="{{ number_format($pageView) }}" title="Total Views" change="" />
            <x-card.card1 icon="bi-eye-fill" color="purple" value="{{ number_format($pageViewToday) }}"
                title="Total Views Today" change="" />
            <x-card.card1 icon="bi-eye-fill" color="purple" value="{{ number_format($pageViewUrl) }}"
                title="Total Views URL" change="" />
            <x-card.card1 icon="bi-newspaper" color="purple" value="{{ $beritaCount }}" title="Total News Published"
                change="" />
        </div>

        <!-- Charts Section -->
        {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div
                class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-200 dark:border-transparent">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex gap-4">
                        <div class="flex items-center"><span
                                class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></span><span class="text-sm">Total
                                Revenue</span></div>
                        <div class="flex items-center"><span class="w-3 h-3 bg-sky-500 rounded-full mr-2"></span><span
                                class="text-sm">Total
                                Sales</span></div>
                    </div>
                    <div class="bg-slate-200 dark:bg-slate-700 p-1 rounded-lg text-xs flex"><button
                            class="px-3 py-1 rounded-md bg-white dark:bg-slate-600">Day</button><button
                            class="px-3 py-1 rounded-md">Week</button><button
                            class="px-3 py-1 rounded-md">Month</button></div>
                </div>
                <div class="h-80 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                    <p class="text-slate-500">Chart will be rendered here</p>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-200 dark:border-transparent">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Profit this week</h4>
                <div class="h-80 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                    <p class="text-slate-500">Bar Chart</p>
                </div>
            </div>
        </div> --}}

        <!-- Table and Chats Section -->
        {{-- <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div
                class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-lg border border-gray-200 dark:border-transparent">
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Top Channels</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="text-xs text-gray-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-700/50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Source</th>
                                <th scope="col" class="px-6 py-3">Visitors</th>
                                <th scope="col" class="px-6 py-3">Revenues</th>
                                <th scope="col" class="px-6 py-3">Sales</th>
                                <th scope="col" class="px-6 py-3">Conversion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-slate-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Google</td>
                                <td class="px-6 py-4">3.5K</td>
                                <td class="px-6 py-4 text-green-500 dark:text-green-400">$5,768</td>
                                <td class="px-6 py-4">590</td>
                                <td class="px-6 py-4 text-indigo-500 dark:text-indigo-400">4.8%</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-slate-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Twitter</td>
                                <td class="px-6 py-4">2.2K</td>
                                <td class="px-6 py-4 text-green-500 dark:text-green-400">$4,635</td>
                                <td class="px-6 py-4">467</td>
                                <td class="px-6 py-4 text-indigo-500 dark:text-indigo-400">4.3%</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-slate-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Github</td>
                                <td class="px-6 py-4">2.1K</td>
                                <td class="px-6 py-4 text-green-500 dark:text-green-400">$4,290</td>
                                <td class="px-6 py-4">420</td>
                                <td class="px-6 py-4 text-indigo-500 dark:text-indigo-400">3.7%</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-slate-700">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">Vimeo</td>
                                <td class="px-6 py-4">1.5K</td>
                                <td class="px-6 py-4 text-green-500 dark:text-green-400">$3,580</td>
                                <td class="px-6 py-4">389</td>
                                <td class="px-6 py-4 text-indigo-500 dark:text-indigo-400">2.5%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-transparent">
                <h4 class="font-bold text-gray-900 dark:text-white p-6 pb-2">Chats</h4>
                <div class="p-4 space-y-1"><a href="#"
                        class="flex items-center gap-4 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"><img
                            src="https://placehold.co/48x48/7e22ce/ffffff?text=DH" alt="chat user"
                            class="w-12 h-12 rounded-full">
                        <div class="flex-1 text-left">
                            <div class="flex justify-between items-start">
                                <p class="font-semibold text-gray-800 dark:text-white">David Heilo</p><span
                                    class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded-full">3</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-slate-400 truncate">Hello, how are you?
                            </p>
                        </div>
                    </a><a href="#"
                        class="flex items-center gap-4 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"><img
                            src="https://placehold.co/48x48/16a34a/ffffff?text=HF" alt="chat user"
                            class="w-12 h-12 rounded-full">
                        <div class="flex-1 text-left">
                            <p class="font-semibold text-gray-800 dark:text-white">Henry Fisher</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400 truncate">I am waiting for you.
                            </p>
                        </div>
                    </a><a href="#"
                        class="flex items-center gap-4 p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"><img
                            src="https://placehold.co/48x48/be123c/ffffff?text=WS" alt="chat user"
                            class="w-12 h-12 rounded-full">
                        <div class="flex-1 text-left">
                            <p class="font-semibold text-gray-800 dark:text-white">Wilium Smith</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400 truncate">Where are you now?</p>
                        </div>
                    </a></div>
            </div>
        </div> --}}
    </main>
    <!-- ===== Main Content End ===== -->
</x-layout>
