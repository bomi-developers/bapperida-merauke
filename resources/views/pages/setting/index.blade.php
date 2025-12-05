<x-layout>
    <x-header />
    <main class="p-6 overflow-y-auto h-[calc(100vh-4rem)]" id="main-container">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Pengaturan & Profil</h2>
        </div>

        {{-- TAB NAVIGATION --}}
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="settingTab" role="tablist">
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 rounded-t-lg border-indigo-600 text-indigo-600 dark:text-indigo-500 dark:border-indigo-500"
                        id="website-tab" data-tabs-target="#website" type="button" role="tab"
                        aria-controls="website" aria-selected="true">
                        <i class="bi bi-globe me-2"></i> Pengaturan Website
                    </button>
                </li>
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400"
                        id="profile-tab" data-tabs-target="#profile" type="button" role="tab"
                        aria-controls="profile" aria-selected="false">
                        <i class="bi bi-building me-2"></i> Profil Dinas
                    </button>
                </li>
                {{-- TAB BARU: BANNER HALAMAN --}}
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400"
                        id="banner-tab" data-tabs-target="#banner" type="button" role="tab" aria-controls="banner"
                        aria-selected="false">
                        <i class="bi bi-images me-2"></i> Banner Halaman
                    </button>
                </li>
            </ul>
        </div>

        <form action="{{ route('website-setting.update') }}" method="POST" enctype="multipart/form-data"
            id="settingForm">
            @csrf
            @method('PUT')

            <div id="myTabContent">

                {{-- TAB 1: WEBSITE SETTINGS --}}
                <div class="" id="website" role="tabpanel" aria-labelledby="website-tab">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- KIRI: INFO UMUM --}}
                        <div
                            class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informasi Umum</h3>
                            <div class="mb-4">
                                <label class="form-label">Nama Kantor</label>
                                <input type="text" name="nama_kantor" value="{{ $settings->nama_kantor ?? '' }}"
                                    class="form-input block w-full" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" rows="3" class="form-input block w-full">{{ $settings->alamat ?? '' }}</textarea>
                            </div>
                            <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <input type="text" name="telepon" placeholder="Telepon"
                                    value="{{ $settings->telepon ?? '' }}" class="form-input block w-full">
                                <input type="email" name="email" placeholder="Email"
                                    value="{{ $settings->email ?? '' }}" class="form-input block w-full">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Website URL</label>
                                <input type="text" name="website" value="{{ $settings->website ?? '' }}"
                                    class="form-input block w-full">
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Maps Embed (iframe)</label>
                                <textarea name="maps_iframe" rows="3" class="form-input block w-full">{{ $settings->maps_iframe ?? '' }}</textarea>
                            </div>

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

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-6 mb-3">SEO Metadata</h3>
                            <div class="mb-4"><label class="form-label">Meta Title</label><input type="text"
                                    name="meta_title" value="{{ $settings->meta_title ?? '' }}"
                                    class="form-input block w-full"></div>
                            <div class="mb-4"><label class="form-label">Meta Description</label>
                                <textarea name="meta_description" rows="2" class="form-input block w-full">{{ $settings->meta_description ?? '' }}</textarea>
                            </div>
                            <div class="mb-4"><label class="form-label">Meta Keywords</label><input type="text"
                                    name="meta_keywords" value="{{ $settings->meta_keywords ?? '' }}"
                                    class="form-input block w-full"></div>

                            <div class="flex items-center gap-2 mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <input type="checkbox" name="is_maintenance" value="1"
                                    {{ $settings->is_maintenance ? 'checked' : '' }}
                                    class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div><label class="text-gray-700 dark:text-gray-300 font-bold">Aktifkan Maintenance
                                        Mode</label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Jika aktif, pengunjung publik
                                        tidak akan bisa mengakses website.</p>
                                </div>
                            </div>
                        </div>

                        {{-- KANAN: BRANDING --}}
                        <div class="lg:col-span-1">
                            <div
                                class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Branding & Logo
                                </h3>
                                <div class="mb-6">
                                    <label class="form-label flex items-center gap-2"><i
                                            class="bi bi-sun text-yellow-500"></i> Logo (Light)</label>
                                    <input type="file" name="logo" class="form-input block w-full text-sm">
                                    @if ($settings->logo)
                                        <div class="mt-2 p-3 bg-gray-100 border rounded text-center"><img
                                                src="{{ asset('storage/' . $settings->logo) }}"
                                                class="h-12 mx-auto object-contain"></div>
                                    @endif
                                </div>
                                <div class="mb-6">
                                    <label class="form-label flex items-center gap-2"><i
                                            class="bi bi-moon-stars text-indigo-400"></i> Logo (Dark)</label>
                                    <input type="file" name="logo_dark" class="form-input block w-full text-sm">
                                    @if ($settings->logo_dark)
                                        <div class="mt-2 p-3 bg-gray-800 border border-gray-600 rounded text-center">
                                            <img src="{{ asset('storage/' . $settings->logo_dark) }}"
                                                class="h-12 mx-auto object-contain"></div>
                                    @endif
                                </div>
                                <div class="mb-6">
                                    <label class="form-label">Favicon</label>
                                    <input type="file" name="favicon" class="form-input block w-full text-sm">
                                    @if ($settings->favicon)
                                        <div class="mt-2"><img src="{{ asset('storage/' . $settings->favicon) }}"
                                                class="h-8 w-8 rounded border"></div>
                                    @endif
                                </div>

                                {{-- HERO DEFAULT --}}
                                <div class="mb-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                    <label class="form-label flex items-center gap-2">
                                        <i class="bi bi-card-image text-blue-500"></i> Header Default
                                    </label>
                                    <input type="file" name="hero_bg" class="form-input block w-full text-sm"
                                        accept="image/*">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Digunakan jika halaman
                                        spesifik tidak diatur.</p>

                                    @if ($settings->hero_bg)
                                        <div class="mt-2 p-1 bg-gray-100 border rounded relative group">
                                            <img src="{{ asset('storage/' . $settings->hero_bg) }}"
                                                class="w-full h-24 object-cover rounded-md">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: PROFIL DINAS --}}
                <div class="hidden" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- KOLOM KIRI: Teks Informasi --}}
                        <div
                            class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                                Informasi Instansi</h3>
                            <div class="mb-6">
                                <label class="form-label flex items-center gap-2"><i class="bi bi-clock-history"></i>
                                    Sejarah Singkat</label>
                                <textarea name="sejarah" rows="6" class="form-input block w-full" placeholder="Sejarah singkat instansi...">{{ $profile->sejarah ?? '' }}</textarea>
                            </div>
                            <div class="mb-8">
                                <label class="form-label flex items-center gap-2"><i class="bi bi-briefcase"></i>
                                    Tugas & Fungsi</label>
                                <textarea name="tugas_fungsi" rows="6" class="form-input block w-full" placeholder="Tugas dan fungsi...">{{ $profile->tugas_fungsi ?? '' }}</textarea>
                            </div>
                            <hr class="border-gray-200 dark:border-gray-700 mb-8">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                                Visi & Misi</h3>
                            <div class="mb-6">
                                <label
                                    class="form-label text-lg flex items-center gap-2 text-indigo-600 dark:text-indigo-400"><i
                                        class="bi bi-eye"></i> Visi</label>
                                <textarea name="visi" rows="4"
                                    class="form-input block w-full shadow-sm focus:ring-2 focus:ring-indigo-200 transition-all"
                                    placeholder="Tuliskan Visi Organisasi...">{{ $profile->visi ?? '' }}</textarea>
                            </div>
                            <div class="flex flex-col">
                                <label
                                    class="form-label text-lg flex items-center gap-2 text-indigo-600 dark:text-indigo-400"><i
                                        class="bi bi-list-check"></i> Misi</label>
                                <div
                                    class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700 flex-grow">
                                    <div id="misi-container" class="space-y-3"></div>
                                    <button type="button" id="add-misi-btn"
                                        class="mt-4 w-full py-2 border-2 border-dashed border-indigo-300 dark:border-indigo-700 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors text-sm font-semibold flex items-center justify-center gap-2"><i
                                            class="bi bi-plus-circle"></i> Tambah Poin Misi Baru</button>
                                </div>
                                <textarea name="misi" id="misi-hidden" class="hidden">{{ $profile->misi ?? '' }}</textarea>
                            </div>
                        </div>
                        {{-- KOLOM KANAN: Struktur Organisasi --}}
                        <div class="lg:col-span-1">
                            <div
                                class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Struktur
                                    Organisasi</h3>
                                <div class="mb-2">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="struktur-file"
                                            class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600 transition-all">
                                            <div
                                                class="flex flex-col items-center justify-center pt-5 pb-6 text-center px-4">
                                                <i class="bi bi-diagram-3 text-4xl text-gray-400 mb-3"></i>
                                                <p class="mb-1 text-sm text-gray-500 dark:text-gray-400"><span
                                                        class="font-semibold text-indigo-600">Klik upload</span> atau
                                                    drag file</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG, PDF (MAX.
                                                    5MB)</p>
                                            </div>
                                            <input id="struktur-file" type="file" name="struktur_organisasi"
                                                class="hidden" />
                                        </label>
                                    </div>
                                    @if ($profile && $profile->struktur_organisasi)
                                        <div
                                            class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <span
                                                    class="text-xs font-semibold text-blue-800 dark:text-blue-200 uppercase tracking-wider">File
                                                    Tersimpan</span>
                                                <i class="bi bi-check-circle-fill text-blue-500"></i>
                                            </div>
                                            <a href="{{ asset('storage/' . $profile->struktur_organisasi) }}"
                                                target="_blank"
                                                class="block w-full text-center text-xs bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-700 px-3 py-2 rounded-md text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors font-medium"><i
                                                    class="bi bi-eye me-1"></i> Lihat File Saat Ini</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 3: BANNER HALAMAN (BARU) --}}
                <div class="hidden" id="banner" role="tabpanel" aria-labelledby="banner-tab">
                    <div
                        class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                        <div class="mb-6 border-b border-gray-200 dark:border-gray-700 pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Banner Per
                                Halaman</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Upload gambar header khusus untuk
                                setiap halaman. Jika kosong, akan menggunakan <strong>Header Default</strong>.</p>
                        </div>

                        {{-- Daftar Halaman --}}
                        @php
                            $pagesList = [
                                'welcome' => 'Beranda Utama',
                                'berita.public.home' => 'Halaman Berita (Index)',
                                'about.struktur-organisasi' => 'Tentang - Struktur Organisasi',
                                'about.pegawai' => 'Tentang - Daftar Pegawai',
                                'about.sejarah' => 'Tentang - Sejarah',
                                'about.tugas-fungsi' => 'Tentang - Tugas & Fungsi',
                                'about.visi-misi' => 'Tentang - Visi Misi',
                                'riset-inovasi.riset' => 'Riset & Inovasi - Publikasi',
                                'riset-inovasi.inovasi' => 'Riset & Inovasi - Pengajuan',
                                'riset-inovasi.data' => 'Riset & Inovasi - Data',
                                'riset-inovasi.kekayaan-intelektual' => 'Riset & Inovasi - HKI',
                                'galeri.public.index' => 'Galeri Foto',
                            ];
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($pagesList as $route => $label)
                                <div
                                    class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                                    <h4
                                        class="font-medium text-gray-800 dark:text-gray-200 mb-3 text-sm flex items-center gap-2">
                                        <i class="bi bi-layout-text-window-reverse text-indigo-500"></i>
                                        {{ $label }}
                                    </h4>

                                    {{-- Preview Gambar --}}
                                    <div
                                        class="relative w-full h-32 bg-gray-200 dark:bg-gray-700 rounded-md overflow-hidden mb-3 group border border-gray-300 dark:border-gray-600">
                                        @if (isset($pageHeroes[$route]))
                                            <img src="{{ asset('storage/' . $pageHeroes[$route]) }}"
                                                class="w-full h-full object-cover">
                                            <div
                                                class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-bold">Terpasang</span>
                                            </div>
                                        @else
                                            {{-- Tampilkan Default jika belum ada --}}
                                            @if ($settings->hero_bg)
                                                <img src="{{ asset('storage/' . $settings->hero_bg) }}"
                                                    class="w-full h-full object-cover opacity-50 grayscale">
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <span
                                                        class="text-gray-800 font-bold text-xs bg-white/80 px-2 py-1 rounded">Default</span>
                                                </div>
                                            @else
                                                <div
                                                    class="flex items-center justify-center h-full text-gray-400 text-xs">
                                                    No Image</div>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Input File --}}
                                    <input type="file" name="page_heroes[{{ $route }}]"
                                        class="block w-full text-xs text-slate-500
                                        file:mr-2 file:py-2 file:px-3
                                        file:rounded-md file:border-0
                                        file:text-xs file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-indigo-900 dark:file:text-indigo-300
                                        cursor-pointer border rounded-md bg-white dark:bg-gray-900 dark:border-gray-600
                                    "
                                        accept="image/*">
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>

            {{-- TOMBOL SIMPAN GLOBAL --}}
            <div
                class="mt-8 flex justify-end gap-3 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <a href="{{ route('home') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Semua Perubahan</button>
            </div>
        </form>
    </main>

    <style>
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
            font-size: 0.875rem;
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
            transition: border-color 0.15s ease-in-out;
        }

        .dark .form-input {
            background-color: #1f2937;
            border-color: #4b5563;
            color: #f9fafb;
        }

        .form-input:focus {
            border-color: #4f46e5;
            outline: none;
            ring: 2px solid rgba(79, 70, 229, 0.1);
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: background-color 0.2s;
            cursor: pointer;
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
            transition: all 0.2s;
            cursor: pointer;
        }

        .dark .btn-secondary {
            background-color: #374151;
            color: #e5e7eb;
        }

        .btn-secondary:hover {
            background-color: #d1d5db;
        }

        .dark .btn-secondary:hover {
            background-color: #4b5563;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- TABS LOGIC ---
            const tabs = [{
                    trigger: document.getElementById('website-tab'),
                    content: document.getElementById('website')
                },
                {
                    trigger: document.getElementById('profile-tab'),
                    content: document.getElementById('profile')
                },
                {
                    trigger: document.getElementById('banner-tab'),
                    content: document.getElementById('banner')
                }
            ];

            function switchTab(activeTab) {
                tabs.forEach(tab => {
                    if (tab.trigger === activeTab.trigger) {
                        tab.trigger.classList.add('border-indigo-600', 'text-indigo-600',
                            'dark:text-indigo-500', 'dark:border-indigo-500');
                        tab.trigger.classList.remove('border-transparent', 'text-gray-500',
                            'dark:text-gray-400');
                        tab.content.classList.remove('hidden');
                    } else {
                        tab.trigger.classList.remove('border-indigo-600', 'text-indigo-600',
                            'dark:text-indigo-500', 'dark:border-indigo-500');
                        tab.trigger.classList.add('border-transparent', 'text-gray-500',
                            'dark:text-gray-400');
                        tab.content.classList.add('hidden');
                    }
                });
            }

            tabs.forEach(tab => {
                tab.trigger.addEventListener('click', () => switchTab(tab));
            });

            // --- DYNAMIC MISI INPUT LOGIC ---
            const misiContainer = document.getElementById('misi-container');
            const addMisiBtn = document.getElementById('add-misi-btn');
            const hiddenMisiInput = document.getElementById('misi-hidden');

            function createMisiInput(value = '') {
                const div = document.createElement('div');
                div.className = 'flex items-center gap-2 group';
                div.innerHTML = `
                    <div class="flex-grow relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-indigo-400 font-bold text-lg">•</span>
                        <input type="text" class="form-input block w-full pl-8 py-2.5 misi-item bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:border-indigo-500" value="${value}" placeholder="Tuliskan poin misi...">
                    </div>
                    <button type="button" class="text-gray-400 hover:text-red-500 p-2 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-all remove-misi-btn opacity-60 group-hover:opacity-100" title="Hapus Baris">
                        <i class="bi bi-trash"></i>
                    </button>
                `;
                misiContainer.appendChild(div);

                div.querySelector('.remove-misi-btn').addEventListener('click', function() {
                    div.remove();
                    updateHiddenInput();
                });

                div.querySelector('.misi-item').addEventListener('input', updateHiddenInput);
            }

            function updateHiddenInput() {
                const inputs = document.querySelectorAll('.misi-item');
                const values = Array.from(inputs).map(input => input.value).filter(val => val.trim() !== '');
                hiddenMisiInput.value = values.join('\n');
            }

            const initialData = hiddenMisiInput.value;
            if (initialData.trim()) {
                const lines = initialData.split('\n');
                lines.forEach(line => createMisiInput(line));
            } else {
                createMisiInput();
            }

            addMisiBtn.addEventListener('click', function() {
                createMisiInput();
            });

            document.getElementById('settingForm').addEventListener('submit', updateHiddenInput);
        });
    </script>
</x-layout>
