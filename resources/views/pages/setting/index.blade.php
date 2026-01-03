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
                {{-- tab hero homepage --}}
                <li class="mr-2" role="presentation">
                    <button
                        class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 text-gray-500 dark:text-gray-400"
                        id="homepage-tab" data-tabs-target="#homepage" type="button" role="tab"
                        aria-controls="banner" aria-selected="false">
                        <i class="bi bi-images me-2"></i> Banner Halaman Utama
                    </button>
                </li>
            </ul>
        </div>
        <div class="my-6">
            <div
                class="bg-indigo-200 dark:bg-indigo-600 border border-indigo-700 dark:border-indigo-200 px-4 py-3 rounded-xl text-indigo-700 dark:text-indigo-200">
                <i class="bi bi-exclamation-triangle text-xl mr-3"></i>Tampilan di halaman utama akan
                berubah
                dalam
                waktu sekitar 1 jam atau saat cache di perbarui.
            </div>
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
                                                class="h-12 mx-auto object-contain">
                                        </div>
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
                {{-- TAB 4: banner hame page --}}
                <div class="" id="homepage" role="tabpanel" aria-labelledby="homepage-tab">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-3">
                        {{-- KOLOM KIRI:  Informasi --}}
                        <div
                            class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                            <h3
                                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-3">
                                Banner Halaman Utama</h3>
                            <div class="mb-6">
                                <label class="form-label flex items-center gap-2"><i class="bi bi-clock-history"></i>
                                    Judul</label>
                                <input name="judul_hero" rows="6" class="form-input block w-full"
                                    placeholder="Judul Hero.." value="{{ $settings->judul_hero ?? '' }}">
                            </div>
                            <div class="mb-6">
                                <label class="form-label flex items-center gap-2"><i class="bi bi-clock-history"></i>
                                    Deskripsi</label>
                                <textarea name="deskripsi_hero" rows="6" class="form-input block w-full" placeholder="Keterangan singkat...">{{ $settings->deskripsi_hero ?? '' }}</textarea>
                            </div>
                        </div>
                        {{-- {{ $galeriItems }} --}}
                        {{-- KOLOM KANAN: file --}}
                        <div class="lg:col-span-1">
                            <input type="hidden" name="file_hero" id="input_file_hero"
                                value="{{ $setting->file_hero ?? '' }}">

                            <div
                                class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Media Hero</h3>
                                    <button type="button" id="btn_hapus_media"
                                        class="text-red-500 text-xs hover:underline {{ isset($setting->file_hero) ? '' : 'hidden' }}">
                                        Hapus Media
                                    </button>
                                </div>

                                <div class="mb-2">
                                    <div id="btn_buka_galeri"
                                        class="group relative flex flex-col items-center justify-center w-full h-48 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 transition-all overflow-hidden">

                                        <div id="preview_container"
                                            class="w-full h-full {{ isset($setting->file_hero) ? '' : 'hidden' }}">
                                            <div id="preview_content" class="w-full h-full">
                                                @if (isset($setting->file_hero))
                                                    @if (preg_match('/\.(mp4|webm|ogg)$/i', $setting->file_hero))
                                                        <video class="w-full h-full object-cover opacity-50" muted
                                                            autoplay loop>
                                                            <source
                                                                src="{{ asset('storage/' . $setting->file_hero) }}">
                                                        </video>
                                                    @else
                                                        <img src="{{ asset('storage/' . $setting->file_hero) }}"
                                                            class="w-full h-full object-cover opacity-50">
                                                    @endif
                                                @endif
                                            </div>
                                            <div
                                                class="absolute inset-0 flex flex-col items-center justify-center text-white bg-black/20">
                                                <i class="bi bi-pencil-square text-2xl mb-2"></i>
                                                <span class="text-xs font-medium">Ganti Media</span>
                                            </div>
                                        </div>

                                        <div id="placeholder_empty"
                                            class="flex flex-col items-center justify-center text-center px-4 {{ isset($setting->file_hero) ? 'hidden' : '' }}">
                                            <i class="bi bi-plus-circle text-4xl text-gray-400 mb-3"></i>
                                            <p class="text-sm text-gray-500">Pilih Media dari Galeri</p>
                                        </div>
                                    </div>
                                    <p id="text_path_hero"
                                        class="mt-2 text-[10px] text-gray-500 truncate italic italic">
                                        {{ $setting->file_hero ?? '' }}</p>
                                </div>
                            </div>
                        </div>

                        <div id="modal_galeri"
                            class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
                            <div
                                class="relative bg-white dark:bg-gray-800 w-full max-w-4xl rounded-2xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                                <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">
                                    <h3 class="font-bold dark:text-white text-lg">Pilih Media Galeri</h3>
                                    <button type="button"
                                        class="btn_tutup_galeri text-red-800 dark:text-red-300 hover:bg-red-200 dark:hover:bg-red-800 rounded-full w-8 h-8 flex items-center justify-center">&times;</button>
                                </div>
                                <div
                                    class="p-6 overflow-y-auto grid grid-cols-2 md:grid-cols-4 gap-4 bg-white dark:bg-gray-900">
                                    @foreach ($galeriItems as $item)
                                        @php $cleanPath = str_replace('\\', '/', $item->file_path); @endphp
                                        <div class="item-galeri group relative aspect-video rounded-xl overflow-hidden bg-black border-2 border-transparent hover:border-indigo-500 cursor-pointer transition-all"
                                            data-path="{{ $cleanPath }}" data-type="{{ $item->tipe_file }}">

                                            @if ($item->tipe_file == 'video')
                                                <video class="w-full h-full object-cover opacity-70">
                                                    <source src="{{ asset('storage/' . $cleanPath) }}">
                                                </video>
                                            @else
                                                <img src="{{ asset('storage/' . $cleanPath) }}"
                                                    class="w-full h-full object-cover opacity-70">
                                            @endif

                                            <div
                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 bg-black/20">
                                                <i class="bi bi-check-circle-fill text-white text-2xl"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>

                    <section
                        class="my-5 p-6 bg-white rounded-xl border border-gray-200 dark:border-indigo-700 shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900  mb-4 border-b border-gray-200  pb-3">
                            Preview Hero</h3>
                        <div
                            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-12 items-center ">
                            <div class="w-full max-w-4xl mx-auto aspect-video">
                                @php
                                    $bgImage = asset('assets/LogoKabMerauke.png');
                                    $file = $settings->file_hero ?? null;
                                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                                    if ($file) {
                                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                                        $isRemote =
                                            str_contains($file, 'https') ||
                                            str_contains($file, 'youtube') ||
                                            str_contains($file, 'vimeo');
                                        $filePath = $isRemote ? $file : asset('storage/' . $file);
                                    } else {
                                        $extension = 'png';
                                        $isRemote = false;
                                        $filePath = $bgImage;
                                    }
                                    function youtubeEmbed($url)
                                    {
                                        preg_match(
                                            '%(?:youtube\.com/(?:watch\?v=|embed/)|youtu\.be/)([^&?/]+)%',
                                            $url,
                                            $matches,
                                        );
                                        return isset($matches[1])
                                            ? 'https://www.youtube.com/embed/' . $matches[1]
                                            : null;
                                    }

                                    $embedUrl = youtubeEmbed($file);
                                @endphp

                                @if ($isRemote)
                                    <iframe src="{{ $embedUrl }}" frameborder="0" loading="lazy"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen class="w-full h-full"></iframe>
                                @elseif (in_array(strtolower($extension), ['mp4', 'webm', 'ogg']))
                                    <video controls class="w-full h-full object-cover">
                                        <source src="{{ $filePath }}" type="video/{{ $extension }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <div class="flex items-center justify-center w-full h-full">
                                        <img src="{{ $filePath }}" alt="Hero Image"
                                            class="w-full h-full object-contain">
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-[#] leading-tight text-[#004299]">
                                    {{ $settings->judul_hero ?? '' }}
                                </h2>
                                <p class="mt-4 text-gray-600">
                                    {{ $settings->deskripsi_hero ?? '' }}
                                </p>
                            </div>
                        </div>
                    </section>
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
    @push('styles')
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
    @endpush
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                    }, {
                        trigger: document.getElementById('homepage-tab'),
                        content: document.getElementById('homepage')
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

        <script>
            $(document).ready(function() {
                const storageUrl = "{{ asset('storage') }}/";

                // 1. Buka Modal
                $('#btn_buka_galeri').on('click', function() {
                    $('#modal_galeri').removeClass('hidden').addClass('flex');
                });

                // 2. Tutup Modal
                $('.btn_tutup_galeri').on('click', function() {
                    $('#modal_galeri').addClass('hidden').removeClass('flex');
                });

                // 3. Pilih Item dari Galeri
                $('.item-galeri').on('click', function() {
                    let filePath = $(this).data('path');
                    let fileType = $(this).data('type');

                    // Update Hidden Input
                    $('#input_file_hero').val(filePath);
                    $('#text_path_hero').text(filePath);

                    // Update Preview
                    $('#placeholder_empty').addClass('hidden');
                    $('#preview_container').removeClass('hidden');
                    $('#btn_hapus_media').removeClass('hidden');

                    let htmlPreview = '';
                    if (fileType === 'video' || filePath.match(/\.(mp4|webm|ogg)$/i)) {
                        htmlPreview = `<video class="w-full h-full object-cover opacity-50" muted autoplay loop>
                                <source src="${storageUrl + filePath}" type="video/mp4">
                           </video>`;
                    } else {
                        htmlPreview =
                            `<img src="${storageUrl + filePath}" class="w-full h-full object-cover opacity-50">`;
                    }

                    $('#preview_content').html(htmlPreview);
                    $('#modal_galeri').addClass('hidden').removeClass('flex');
                });

                // 4. Hapus Pilihan
                $('#btn_hapus_media').on('click', function(e) {
                    e.stopPropagation(); // Biar gak buka modal
                    $('#input_file_hero').val('');
                    $('#text_path_hero').text('');
                    $('#preview_container').addClass('hidden');
                    $('#placeholder_empty').removeClass('hidden');
                    $(this).addClass('hidden');
                });
            });
        </script>
    @endpush
</x-layout>
