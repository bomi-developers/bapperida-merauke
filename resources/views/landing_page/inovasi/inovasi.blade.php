<x-landing.layout>
    <div id="first-section" class="relative min-h-screen flex flex-col items-center justify-center px-4 -mt-[300px]">
        <div class="w-full max-w-3xl p-8 space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto mb-4 bg-blue-500 w-16 h-16 flex items-center justify-center rounded-full">
                    <i class="bi bi-file-earmark-text text-white text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-blue-600">Formulir Proposal Inovasi</h2>
            </div>
            <div>
                <p class="text-center text-gray-600">Silakan isi formulir di bawah ini untuk mengajukan proposal inovasi
                    Anda. Pastikan semua informasi yang diberikan akurat dan lengkap.</p>

                <div class="p-3 border-2 border-blue-600 mt-2 rounded-2xl">
                    <b>Petunjuk Pengisian :</b>
                    <ol class="list-decimal list-inside text-left mt-2 space-y-1">
                        <li>Input Berwana merah menandakan wajib untuk diisi</li>
                        <li>Nomor HP berawalan 08xxxxxx</li>
                        <li>Mengisi alamat Email aktif dan sedang digunakan</li>
                    </ol>
                </div>
            </div>
            <!-- IDENTITAS PENGIRIM -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700  mb-4">Identitas Pengirim</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input id="nama" type="text" name="nama" placeholder="Nama Pengirim"
                        class="border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                    <input id="no_hp" type="text" name="no_hp" placeholder="No HP : 08xxxxxx" maxlength="15"
                        pattern="^08[0-9]{8,13}$" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        class="border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                        required>
                </div>
                <div class="relative">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email Aktif</label>
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
            <!-- ISI PROPOSAL -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700  mb-4">Isi Proposal</h3>
                <input id="judul" type="text" name="judul" placeholder="Judul Proposal"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>
                <textarea id="latar_belakang" name="latar_belakang" rows="4" placeholder="Latar Belakang (min 100 karakter)"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
            </div>
            <!--  FILE PROPOSAL -->
            <div class="p-6 bg-white border-2 border-blue-600 rounded-2xl shadow-md space-y-4">
                <h3 class="text-xl font-semibold text-blue-700  mb-4">File Proposal</h3>
                <input id="link_video" type="url" name="link_video" placeholder="Link Video Proposal (Opsional)"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                <input id="file" type="file" name="file" accept=".pdf,.doc,.docx"
                    class="w-full border-2 border-blue-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 outline-none"
                    required>
            </div>
            <!-- CAPTCHA + SUBMIT -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div
                    class="w-full md:w-1/2 flex justify-center md:justify-start py-3 rounded-xl font-semibold text-gray-600">
                    {!! NoCaptcha::display() !!}
                </div>
                <button id="submit-btn" type="submit"
                    class="w-full md:w-1/2 bg-blue-600 text-white py-5 rounded-xl shadow-md hover:bg-blue-700 transition font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Submit
                </button>
            </div>
        </div>
    </div>
    <div id="alert-container"
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/20 z-50 ">
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
        class="hidden fixed inset-0 flex items-center justify-center backdrop-blur-sm bg-black/20 z-50 ">
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
            const linkInput = document.getElementById('link_video');
            const alertBoxLink = document.getElementById('alert-container-link');
            const alertMsgLink = document.getElementById('alert-message-link');
            const alertOkLink = document.getElementById('alert-ok-link');
            let sudahAlertLink = false;

            // Fungsi popup cantik
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

            // Cek format saat kehilangan fokus
            linkInput.addEventListener('blur', function() {
                const link = this.value.trim();

                if (link !== '' && !link.startsWith('https://') && !sudahAlertLink) {
                    sudahAlertLink = true;
                    showAlertLink('Link harus diawali dengan https://', () => {
                        setTimeout(() => this.focus(), 0);
                    });
                }
            });

            // Reset alert jika user mengedit
            linkInput.addEventListener('input', () => {
                sudahAlertLink = false;
            });
        </script>
        <script>
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
        </script>
        <script>
            const inputs = [
                document.getElementById('nama'),
                document.getElementById('no_hp'),
                document.getElementById('email'),
                document.getElementById('judul'),
                document.getElementById('latar_belakang'),
                document.getElementById('file')
            ];

            const latarInput = document.getElementById('latar_belakang');
            const hpInput = document.getElementById('no_hp');
            const emailInput = document.getElementById('email');
            const emailIcon = document.getElementById('email-icon');
            const statusText = document.getElementById('email-status');
            const submitBtn = document.getElementById('submit-btn');
            let emailValid = false;

            // ✅ Validasi Form Global
            function validateForm() {
                let allFilled = true;

                inputs.forEach(input => {
                    if (input === latarInput) {
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

            // 🎨 Update border warna saat mengetik
            inputs.forEach(input => {
                input.addEventListener('input', validateForm);
                input.addEventListener('change', validateForm);
            });

            // 📧 Cek validasi email
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

            // 🚀 SUBMIT FORM
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
                formData.append('link_video', document.getElementById('link_video').value);
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

            // Inisialisasi awal
            validateForm();
        </script>
    @endpush
</x-landing.layout>
