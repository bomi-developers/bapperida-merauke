<x-landing.layout>
    <div id="first-section"
        class="relative min-h-screen flex flex-col items-center justify-center px-4 -mt-[300px] py-20">
        <div class="w-full max-w-4xl p-8 space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto mb-4 bg-blue-500 w-16 h-16 flex items-center justify-center rounded-full">
                    <i class="bi bi-lightbulb text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-blue-600">Formulir Ide Inovasi</h2>
                <p class="text-sm text-gray-500 mt-2">Laboratorium Inovasi Kabupaten</p>
            </div>

            <div>
                <p class="text-center text-gray-600">Silakan isi formulir di bawah ini untuk mengajukan ide inovasi Anda.
                    Pastikan semua informasi yang diberikan akurat dan lengkap.</p>

                <div class="p-3 border-2 border-blue-600 mt-2 rounded-2xl">
                    <b>Petunjuk Pengisian :</b>
                    <ol class="list-decimal list-inside text-left mt-2 space-y-1">
                        <li>Input berwarna merah menandakan wajib untuk diisi</li>
                        <li>Nomor HP berawalan 08xxxxxx</li>
                        <li>Mengisi alamat Email aktif dan sedang digunakan</li>
                        <li>Isi setiap field dengan lengkap dan jelas</li>
                    </ol>
                </div>
            </div>

            <!-- IDENTITAS PENGIRIM -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Identitas Pengirim</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input id="nama" type="text" name="nama" placeholder="Nama Pengirim *"
                        class="border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                    <input id="no_hp" type="text" name="no_hp" placeholder="No HP : 08xxxxxx *" maxlength="15"
                        pattern="^08[0-9]{8,13}$" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                </div>
                <div class="relative">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Aktif *</label>
                    <div class="relative">
                        <input id="email" type="email" name="email"
                            class="mt-1 w-full border rounded-xl px-5 py-2 pr-12 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            placeholder="contoh@email.com" required>
                        <span id="email-icon"
                            class="absolute right-3 top-1/2 -translate-y-1/2 hidden text-xl transition-all duration-300"></span>
                    </div>
                    <p id="email-status" class="text-sm mt-1 text-center transition-all duration-300"></p>
                </div>
            </div>

            <!-- LATAR BELAKANG -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Latar Belakang</h3>
                <p class="text-sm text-gray-600 mb-2">Masalah yang dihadapi atau kondisi yang ingin diperbaiki</p>
                <textarea id="latar_belakang" name="latar_belakang" rows="5"
                    placeholder="Jelaskan masalah/kondisi yang dihadapi (min 100 karakter) *"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
                <p class="text-xs text-gray-500">Karakter: <span id="char-count">0</span>/100 minimum</p>
            </div>

            <!-- IDE INOVASI -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Ide Inovasi</h3>
                <input id="judul" type="text" name="judul" placeholder="Judul/Nama Ide Inovasi *"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>
                <textarea id="ide_inovasi" name="ide_inovasi" rows="4" placeholder="Jelaskan ide inovasi Anda secara singkat *"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
            </div>

            <!-- TUJUAN DAN TARGET -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Tujuan Inovasi & Target Perubahan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan Inovasi *</label>
                        <textarea id="tujuan_inovasi" name="tujuan_inovasi" rows="4" placeholder="Apa tujuan dari inovasi ini?"
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Target Perubahan *</label>
                        <textarea id="target_perubahan" name="target_perubahan" rows="4" placeholder="Perubahan apa yang diharapkan?"
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
                    </div>
                </div>
            </div>

            <!-- STAKEHOLDER & SUMBER DAYA -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Stakeholder & Sumber Daya</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stakeholder Inovasi *</label>
                        <textarea id="stakeholder" name="stakeholder" rows="4" placeholder="Siapa saja yang terlibat dalam inovasi ini?"
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Contoh: OPD terkait, Perusahaan, Masyarakat</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Daya (SDM) *</label>
                        <textarea id="sdm" name="sdm" rows="4" placeholder="SDM apa yang dibutuhkan?"
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Contoh: PNS, Operator, Tenaga Ahli</p>
                    </div>
                </div>
            </div>

            <!-- PENERIMA MANFAAT & KEBARUAN -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Penerima Manfaat & Kebaruan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penerima Manfaat *</label>
                        <textarea id="penerima_manfaat" name="penerima_manfaat" rows="4" placeholder="Siapa yang akan mendapat manfaat?"
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                            required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Contoh: Masyarakat umum, Pencari kerja</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kebaruan Inovasi *</label>
                        <textarea id="kebaruan" name="kebaruan" rows="4" placeholder="Apa yang baru dari inovasi ini?"
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                            required></textarea>
                        <p class="text-xs text-gray-500 mt-1">Contoh: Sistem online, Akses 24/7</p>
                    </div>
                </div>
            </div>

            <!-- DESKRIPSI SINGKAT -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Deskripsi Singkat Ide Inovasi</h3>
                <textarea id="deskripsi_ide" name="deskripsi_ide" rows="5"
                    placeholder="Jelaskan secara singkat keseluruhan ide inovasi Anda *"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                    required></textarea>
            </div>

            <!-- KETERANGAN TAMBAHAN -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Keterangan Tambahan</h3>
                <textarea id="keterangan" name="keterangan" rows="3"
                    placeholder="Waktu pelaksanaan, anggaran, atau informasi tambahan lainnya"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>
                <p class="text-xs text-gray-500">Opsional - Informasi pendukung lainnya</p>
            </div>

            <!-- FILE PROPOSAL -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700 mb-4">Dokumen Pendukung</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Link Video Proposal</label>
                        <input id="link_video" type="url" name="link_video" placeholder="https://..."
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                        <p class="text-xs text-gray-500 mt-1">Opsional - Link YouTube, Google Drive, dll</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Proposal *</label>
                        <input id="file" type="file" name="file" accept=".pdf,.doc,.docx"
                            class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                            required>
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX (Max: 10MB)</p>
                    </div>
                </div>
            </div>

            <!-- CAPTCHA + SUBMIT -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div
                    class="w-full md:w-1/2 flex justify-center md:justify-start py-3 rounded-xl font-semibold text-gray-600">
                    {!! NoCaptcha::display() !!}
                </div>
                <button id="submit-btn" type="submit"
                    class="w-full md:w-1/2 bg-blue-600 text-white py-5 rounded-xl shadow-md hover:bg-blue-700 transition font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <i class="bi bi-send-fill mr-2"></i> Kirim Proposal
                </button>
            </div>
        </div>
    </div>

    <!-- Alert Modal -->
    <div id="alert-container"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/20 z-50">
        <div class="bg-red-500 text-white rounded-xl p-6 shadow-xl text-center w-80 transform transition-all scale-95">
            <i class="bi bi-exclamation-triangle-fill text-white text-6xl mb-4"></i>
            <p id="alert-message" class="text-xl mb-4">Pesan alert</p>
            <button id="alert-ok"
                class="w-full bg-white hover:bg-grey-400 text-red-500 px-4 py-2 rounded-lg transition">
                OK
            </button>
        </div>
    </div>

    <div id="alert-container-link"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/20 z-50">
        <div class="bg-red-500 text-white rounded-xl p-6 shadow-xl text-center w-80 transform transition-all scale-95">
            <i class="bi bi-exclamation-triangle-fill text-white text-6xl mb-4"></i>
            <p id="alert-message-link" class="text-xl mb-4">Pesan alert</p>
            <button id="alert-ok-link"
                class="w-full bg-white hover:bg-grey-400 text-red-500 px-4 py-2 rounded-lg transition">
                OK
            </button>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {!! NoCaptcha::renderJs() !!}
        <script>
            // Character counter
            const latarBelakang = document.getElementById('latar_belakang');
            const charCount = document.getElementById('char-count');

            latarBelakang.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });

            // Link validation
            const linkInput = document.getElementById('link_video');
            const alertBoxLink = document.getElementById('alert-container-link');
            const alertMsgLink = document.getElementById('alert-message-link');
            const alertOkLink = document.getElementById('alert-ok-link');
            let sudahAlertLink = false;

            function showAlertLink(msg, callback) {
                alertMsgLink.textContent = msg;
                alertBoxLink.classList.remove('hidden');
                setTimeout(() => alertBoxLink.querySelector('div').classList.add('scale-100'), 10);
                alertOkLink.onclick = () => {
                    alertBoxLink.classList.add('hidden');
                    alertBoxLink.querySelector('div').classList.remove('scale-100');
                    if (callback) callback();
                };
            }

            linkInput.addEventListener('blur', function() {
                const link = this.value.trim();
                if (link !== '' && !link.startsWith('https://') && !sudahAlertLink) {
                    sudahAlertLink = true;
                    showAlertLink('Link harus diawali dengan https://', () => {
                        setTimeout(() => this.focus(), 0);
                    });
                }
            });

            linkInput.addEventListener('input', () => {
                sudahAlertLink = false;
            });

            // Phone validation
            const noHpInput = document.getElementById('no_hp');
            const alertBox = document.getElementById('alert-container');
            const alertMsg = document.getElementById('alert-message');
            const alertOk = document.getElementById('alert-ok');
            let sudahAlert = false;

            function showAlert(msg, callback) {
                alertMsg.textContent = msg;
                alertBox.classList.remove('hidden');
                setTimeout(() => {
                    alertBox.querySelector('div').classList.add('scale-100');
                }, 10);

                alertOk.onclick = () => {
                    alertBox.classList.add('hidden');
                    alertBox.querySelector('div').classList.remove('scale-100');
                    if (callback) callback();
                };
            }

            noHpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                sudahAlert = false;
            });

            noHpInput.addEventListener('blur', function() {
                const hp = this.value.trim();
                if (hp !== '' && !/^08[0-9]{8,13}$/.test(hp) && !sudahAlert) {
                    sudahAlert = true;
                    showAlert('Nomor HP tidak valid. Harus diawali 08 dan hanya berisi angka.', () => {
                        setTimeout(() => this.focus(), 0);
                    });
                }
            });

            // Form validation
            const inputs = [
                document.getElementById('nama'),
                document.getElementById('no_hp'),
                document.getElementById('email'),
                document.getElementById('judul'),
                document.getElementById('latar_belakang'),
                document.getElementById('ide_inovasi'),
                document.getElementById('tujuan_inovasi'),
                document.getElementById('target_perubahan'),
                document.getElementById('stakeholder'),
                document.getElementById('sdm'),
                document.getElementById('penerima_manfaat'),
                document.getElementById('kebaruan'),
                document.getElementById('deskripsi_ide'),
                document.getElementById('file')
            ];

            const hpInput = document.getElementById('no_hp');
            const emailInput = document.getElementById('email');
            const emailIcon = document.getElementById('email-icon');
            const statusText = document.getElementById('email-status');
            const submitBtn = document.getElementById('submit-btn');
            let emailValid = false;

            function validateForm() {
                let allFilled = true;

                inputs.forEach(input => {
                    if (input === latarBelakang) {
                        const length = input.value.trim().length;
                        if (length < 100) {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-green-500');
                            allFilled = false;
                        } else {
                            input.classList.remove('border-red-500');
                            input.classList.add('border-green-500');
                        }
                    } else if (input === hpInput) {
                        const length = input.value.trim().length;
                        if (length < 10) {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-green-500');
                            allFilled = false;
                        } else {
                            input.classList.remove('border-red-500');
                            input.classList.add('border-green-500');
                        }
                    } else {
                        if (input.value.trim() === '') {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-green-500');
                            allFilled = false;
                        } else {
                            input.classList.remove('border-red-500');
                            input.classList.add('border-green-500');
                        }
                    }
                });

                submitBtn.disabled = !(allFilled && emailValid);
            }

            inputs.forEach(input => {
                input.addEventListener('input', validateForm);
                input.addEventListener('change', validateForm);
            });

            // Email validation
            emailInput.addEventListener('blur', () => {
                const email = emailInput.value.trim();
                if (!email) {
                    emailValid = false;
                    validateForm();
                    return;
                }

                emailIcon.classList.remove('hidden');
                emailIcon.innerHTML = `<i class="bi bi-arrow-repeat animate-spin text-gray-400"></i>`;
                statusText.textContent = 'Memeriksa email...';
                statusText.className = 'text-sm text-blue-500 text-center';

                fetch(`/riset-inovasi/api/check-email?email=${encodeURIComponent(email)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.valid) {
                            emailIcon.innerHTML = `<i class="bi bi-check-circle-fill text-green-500"></i>`;
                            statusText.textContent = 'Email aktif dan valid';
                            statusText.className = 'text-sm text-green-600 text-center';
                            emailValid = true;
                        } else {
                            emailIcon.innerHTML = `<i class="bi bi-x-circle-fill text-red-500"></i>`;
                            statusText.textContent = 'Email tidak valid atau tidak aktif';
                            statusText.className = 'text-sm text-red-600 text-center';
                            emailValid = false;
                        }
                        validateForm();
                    })
                    .catch(() => {
                        emailIcon.innerHTML = `<i class="bi bi-exclamation-triangle-fill text-yellow-500"></i>`;
                        statusText.textContent = 'Gagal memeriksa email';
                        statusText.className = 'text-sm text-yellow-600 text-center';
                        emailValid = false;
                        validateForm();
                    });
            });

            // Submit form
            submitBtn.addEventListener('click', (e) => {
                e.preventDefault();

                if (!grecaptcha.getResponse()) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Verifikasi CAPTCHA',
                        text: 'Silakan verifikasi CAPTCHA terlebih dahulu.'
                    });
                    return;
                }

                const formData = new FormData();
                inputs.forEach(input => {
                    if (input.files) formData.append(input.name, input.files[0]);
                    else formData.append(input.name, input.value);
                });

                // Tambahkan field opsional
                formData.append('link_video', document.getElementById('link_video').value);
                formData.append('keterangan', document.getElementById('keterangan').value);
                formData.append('g-recaptcha-response', grecaptcha.getResponse());

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('riset-inovasi.proposal.store') }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                Swal.fire({
                    title: 'Mengunggah...',
                    html: '<div id="progress-text">0%</div><div class="w-full bg-gray-200 rounded-full h-3 mt-2"><div id="progress-bar" class="bg-blue-500 h-3 rounded-full" style="width: 0%;"></div></div>',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                xhr.upload.addEventListener('progress', (event) => {
                    if (event.lengthComputable) {
                        const percent = Math.round((event.loaded / event.total) * 100);
                        document.getElementById('progress-text').textContent = percent + "%";
                        document.getElementById('progress-bar').style.width = percent + "%";
                    }
                });

                xhr.onload = function() {
                    Swal.close();
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        }).then(() => window.location.reload());
                    } else if (xhr.status === 422) {
                        const response = JSON.parse(xhr.responseText);
                        let errorMessages = Object.values(response.errors).flat().join("\n");
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: errorMessages
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal mengunggah proposal. Silakan coba lagi.'
                        });
                    }
                };

                xhr.send(formData);
            });

            validateForm();
        </script>
    @endpush
</x-landing.layout>
