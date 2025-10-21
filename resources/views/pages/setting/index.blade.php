<x-layout>
    <x-header />
    <main class="p-6 overflow-y-auto h-[calc(100vh-4rem)]" id="main-container">
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Pengaturan Website</h2>

        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm text-gray-600 dark:text-gray-300">
            <a href="{{ route('home') }}" class="hover:underline text-primary">Dashboard</a> /
            <span>Website Settings</span>
        </nav>

        {{-- Form Utama --}}
        <form action="{{ route('website-setting.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Kolom Kiri --}}
                <div
                    class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Umum</h3>

                    {{-- Nama Kantor --}}
                    <div class="mb-4">
                        <label class="form-label">Nama Kantor</label>
                        <input type="text" name="nama_kantor" value="{{ $settings->nama_kantor ?? '' }}"
                            class="form-input block w-full" required>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-4">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="3" class="form-input block w-full">{{ $settings->alamat ?? '' }}</textarea>
                    </div>

                    {{-- Telepon & Email --}}
                    <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="telepon" placeholder="Telepon"
                            value="{{ $settings->telepon ?? '' }}" class="form-input block w-full">
                        <input type="email" name="email" placeholder="Email" value="{{ $settings->email ?? '' }}"
                            class="form-input block w-full">
                    </div>

                    {{-- Website --}}
                    <div class="mb-4">
                        <label class="form-label">Website</label>
                        <input type="text" name="website" value="{{ $settings->website ?? '' }}"
                            class="form-input block w-full">
                    </div>

                    {{-- Logo & Favicon --}}
                    <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Logo</label>
                            <input type="file" name="logo" class="form-input block w-full">
                            @if ($settings->logo)
                                <img src="{{ asset($settings->logo) }}" alt="Logo"
                                    class="mt-2 h-16 rounded-md border">
                            @endif
                        </div>
                        <div>
                            <label class="form-label">Favicon</label>
                            <input type="file" name="favicon" class="form-input block w-full">
                            @if ($settings->favicon)
                                <img src="{{ asset($settings->favicon) }}" alt="Favicon"
                                    class="mt-2 h-10 rounded-md border">
                            @endif
                        </div>
                    </div>

                    {{-- Maps --}}
                    <div class="mb-4">
                        <label class="form-label">Maps Embed (iframe)</label>
                        <textarea name="maps_iframe" rows="3" class="form-input block w-full">{{ $settings->maps_iframe ?? '' }}</textarea>
                    </div>

                    {{-- Media Sosial --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-6 mb-3">Media Sosial</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input type="text" name="facebook" placeholder="Facebook"
                            value="{{ $settings->facebook ?? '' }}" class="form-input">
                        <input type="text" name="instagram" placeholder="Instagram"
                            value="{{ $settings->instagram ?? '' }}" class="form-input">
                        <input type="text" name="twitter" placeholder="Twitter"
                            value="{{ $settings->twitter ?? '' }}" class="form-input">
                        <input type="text" name="linkedin" placeholder="LinkedIn"
                            value="{{ $settings->linkedin ?? '' }}" class="form-input">
                        <input type="text" name="youtube" placeholder="YouTube"
                            value="{{ $settings->youtube ?? '' }}" class="form-input">
                    </div>

                    {{-- SEO --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-6 mb-3">SEO Metadata</h3>
                    <div class="mb-4">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" value="{{ $settings->meta_title ?? '' }}"
                            class="form-input block w-full">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" rows="3" class="form-input block w-full">{{ $settings->meta_description ?? '' }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Meta Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ $settings->meta_keywords ?? '' }}"
                            class="form-input block w-full">
                    </div>

                    {{-- Maintenance Mode --}}
                    <div class="flex items-center gap-2 mt-6">
                        <input type="checkbox" name="is_maintenance" value="1"
                            {{ $settings->is_maintenance ? 'checked' : '' }}
                            class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary">
                        <label class="text-gray-700 dark:text-gray-300">Aktifkan Maintenance Mode</label>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pratinjau Website</h3>

                        {{-- Logo --}}
                        @if ($settings->logo)
                            <div class="mb-4">
                                <strong class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Logo</strong>
                                <img src="{{ asset($settings->logo) }}" class="h-16 rounded-md border">
                            </div>
                        @endif

                        {{-- Favicon --}}
                        @if ($settings->favicon)
                            <div class="mb-4">
                                <strong class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Favicon</strong>
                                <img src="{{ asset($settings->favicon) }}" class="h-10 rounded-md border">
                            </div>
                        @endif

                        {{-- Maps --}}
                        <div>
                            <strong class="block text-sm text-gray-700 dark:text-gray-300 mb-2">Maps</strong>
                            <div class="rounded overflow-hidden border border-gray-200 dark:border-gray-700">
                                {!! $settings->maps_iframe ?? '<p class="text-sm text-gray-500 p-3">Belum ada maps</p>' !!}
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="mt-6 flex gap-2">
                            <a href="{{ route('home') }}" class="btn-secondary w-full text-center">Batal</a>
                            <button type="submit" class="btn-primary w-full">Simpan Pengaturan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    {{-- Tambahkan helper style --}}
    <style>
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .dark .form-label {
            color: #d1d5db;
        }

        .form-input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.875rem;
            background-color: #fff;
            color: #111827;
        }

        .dark .form-input {
            background-color: #1f2937;
            border-color: #4b5563;
            color: #f9fafb;
        }

        .form-input:focus {
            border-color: #4f46e5;
            outline: none;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: background-color 0.2s;
            text-align: center;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .btn-secondary {
            background-color: #e5e7eb;
            color: #1f2937;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-align: center;
            transition: all 0.2s;
        }

        .dark .btn-secondary {
            background-color: #4b5563;
            color: #f9fafb;
        }

        .btn-secondary:hover {
            background-color: #d1d5db;
        }

        .dark .btn-secondary:hover {
            background-color: #6b7280;
        }
    </style>
</x-layout>
