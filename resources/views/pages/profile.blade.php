<x-layout>
    <x-header />

    <main class="h-screen overflow-y-auto">
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <div class="mx-auto max-w-270">

                <!-- Breadcrumb Start -->
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Pengaturan Akun
                    </h2>
                </div>
                <!-- Breadcrumb End -->

                <div class="grid grid-cols-5 gap-8">

                    <!-- Kolom Kiri -->
                    <div class="col-span-5 xl:col-span-2">

                        <!-- Informasi Pribadi -->
                        @if (auth()->user()->role != 'super_admin' && $pegawai != null)
                            <div
                                class="mb-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
                                <div class="border-b  px-7 py-4 border-gray-300 dark:border-gray-600">
                                    <h3 class="font-medium text-black dark:text-white">Informasi Pribadi</h3>
                                </div>

                                <form class="p-6">
                                    @csrf
                                    <input type="hidden" id="pegawai_id" name="id">

                                    <x-input label="Nama Lengkap" id="nama" name="nama" type="text"
                                        value="{{ $pegawai->nama }}" />
                                    <x-input label="NIP" id="nip" name="nip" type="text"
                                        value="{{ $pegawai->nip }}" />
                                    <x-input label="NIK" id="nik" name="nik" type="text"
                                        value="{{ $pegawai->nik }}" />
                                    <x-textarea label="Alamat" name="alamat" rows="4" placeholder="Alamat Rumah"
                                        --}} slot="{{ $pegawai->alamat }}" />

                                    <div class="flex justify-end gap-3 pt-4">
                                        <button type="submit"
                                            class="px-4 py-3 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div
                                class="mb-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
                                <div class="border-b  px-7 py-4 border-gray-300 dark:border-gray-600">
                                    <h3 class="font-medium text-black dark:text-white">Perbarui Akun</h3>
                                </div>

                                <form class="p-6">
                                    @csrf
                                    <x-input label="Username" id="name" name="name" type="text"
                                        value="{{ Auth::user()->name }}" />
                                    <x-input label="Email" id="email" name="email" type="email"
                                        value="{{ Auth::user()->email }}" />

                                    <div class="flex justify-end gap-3 pt-4">
                                        <button type="submit"
                                            class="px-4 py-3 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Perbarui Password -->
                        <div
                            class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">
                            <div class="border-b  px-7 py-4 border-gray-300 dark:border-gray-600">
                                <h3 class="font-medium text-black dark:text-white">Perbarui Password</h3>
                            </div>

                            <form id="formPegawai" class="p-6">
                                @csrf
                                <x-input label="Password Lama" id="password" name="password" type="password" />
                                <x-input label="Password Baru" id="new_password" name="new_password" type="password" />

                                <div class="flex justify-end gap-3 pt-4">
                                    <button type="submit"
                                        class="px-4 py-3 text-sm font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                                        Simpan Password Baru
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-span-5 xl:col-span-3">

                        <div
                            class=" rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg overflow-hidden transition-colors duration-300">

                            <div class=" border-b  px-7 py-4 border-gray-300 dark:border-gray-600">
                                <h3 class="font-medium text-black dark:text-white">Aktifitas Login Akun</h3>
                            </div>
                            <table class="min-w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                <thead
                                    class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-200 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 whitespace-nowrap">IP Address</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Device</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Login At</th>
                                        <th class="px-4 py-3 whitespace-nowrap">Logout At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                            <td class="px-4 py-3 whitespace-nowrap">{{ $log->ip_address }}</td>
                                            <td class="px-4 py-3 truncate max-w-[250px]">{{ $log->user_agent }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">{{ $log->logged_in_at }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                {!! $log->logged_out_at ??
                                                    '<span class="px-2 py-1 border border-red-700 bg-red-200 rounded-full text-red-700"> Auto </span>' !!}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="px-4 py-3 text-center text-gray-500 dark:text-gray-300">
                                                Belum ada aktivitas login
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</x-layout>
