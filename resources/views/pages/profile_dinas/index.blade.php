<x-layout>
    <x-header />

    <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <div class="mx-auto max-w-3xl">

                <!-- Title -->
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-title-md2 font-bold text-black dark:text-white">
                        Profil Instansi
                    </h2>
                </div>

                <!-- Form Section -->
                <div
                    class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                        <h3 class="font-medium text-black dark:text-white">Informasi Profil</h3>
                    </div>
                    <div class="p-7">
                        <form id="profileForm" enctype="multipart/form-data">
                            @csrf
                            <!-- Visi -->
                            <x-textarea label="Visi" name="visi"
                                rows="4">{{ $profile->visi ?? '' }}</x-textarea>
                            <!-- Misi -->
                            <x-textarea label="Misi" name="misi"
                                rows="4">{{ $profile->misi ?? '' }}</x-textarea>
                            <!-- Sejarah -->
                            <x-form.trix label="Sejarah" id="sejarah" name="sejarah" :value="$profile->sejarah ?? ''" />
                            <!-- Tugas & Fungsi (Trix Editor) -->
                            <x-form.trix label="Tugas & Fungsi" id="tugas_fungsi" name="tugas_fungsi"
                                :value="$profile->tugas_fungsi ?? ''" />

                            <!-- Struktur Organisasi -->
                            <div class="mb-5.5">
                                <label class="block text-sm font-medium text-black dark:text-white">Struktur
                                    Organisasi</label>
                                <input type="file" name="struktur_organisasi"
                                    class="w-full rounded border border-stroke bg-gray px-4 py-3 font-medium 
                                    text-black dark:border-strokedark dark:bg-meta-4 dark:text-white">

                                @if ($profile && $profile->struktur_organisasi)
                                    <p class="mt-2 text-sm">
                                        <button type="button"
                                            onclick="openStrukturPopup('{{ asset('storage/' . $profile->struktur_organisasi) }}')"
                                            class="text-blue-500 underline">
                                            Lihat Struktur
                                        </button>
                                    </p>
                                @endif
                            </div>

                            <!-- Submit -->
                            <div class="flex justify-end gap-4.5">

                                <button type="submit"
                                    class="flex justify-center rounded bg-primary px-6 py-2 font-medium text-gray hover:bg-opacity-90">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Modal Popup -->
    <div id="strukturModal"
        class="fixed inset-0 hidden bg-black/60 flex items-center justify-center z-50 backdrop-blur">
        <div class="bg-white dark:bg-boxdark p-4 rounded-lg w-1/2 h-1/2 relative flex flex-col">

            <!-- Close X -->
            <button onclick="closeStrukturPopup()"
                class="absolute top-2 right-2 text-red-600 text-xl font-bold">&times;</button>

            <!-- Iframe -->
            <iframe id="strukturFrame" src="" class="w-full flex-1 border rounded mb-4"></iframe>

            <!-- Cancel button -->
            <div class="flex justify-center">
                <button onclick="closeStrukturPopup()"
                    class="px-4 py-2 bg-danger text-white rounded hover:bg-danger transition">
                    Cancel
                </button>
            </div>
        </div>
    </div>
    <script>
        function openStrukturPopup(url) {
            document.getElementById('strukturFrame').src = url;
            document.getElementById('strukturModal').classList.remove('hidden');
        }

        function closeStrukturPopup() {
            document.getElementById('strukturModal').classList.add('hidden');
            document.getElementById('strukturFrame').src = "";
        }

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('admin.profile-dinas.store') }}", {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    location.reload();
                })
                .catch(err => console.error(err));
        });
    </script>
</x-layout>
