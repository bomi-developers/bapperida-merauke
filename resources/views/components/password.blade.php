@php
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Auth;
@endphp

@if (Hash::check('PegawaiBAPPERIDA', Auth::user()->password))
    <div id="alertAkun" onclick="closeIfOutside(event, 'alertAkun')"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm flex items-center justify-center z-50">

        <div class="relative w-full max-w-lg bg-red-500 dark:bg-red-900 rounded-2xl shadow-xl p-6 border border-red-200 dark:border-red-700"
            onclick="event.stopPropagation()">

            <h3 class="text-xl font-semibold mb-5 text-white border-b border-white pb-2">
                <strong>Password Anda masih default!</strong><br>Silakan ganti password Anda.
            </h3>

            <!-- FORM GANTI PASSWORD -->
            <form id="formGantiPassword">
                @csrf

                <label class="block text-white font-medium mb-1">Password Default</label>
                <input type="password" id="password" name="password"
                    class="w-full px-4 py-2 mb-3 text-sm border rounded-lg">

                <label class="block text-white font-medium mb-1">Password Baru</label>
                <input type="password" id="new-password" name="new-password"
                    class="w-full px-4 py-2 text-sm border rounded-lg">

                <div class="flex justify-center gap-3 pt-5">

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-3 text-lg font-medium rounded-lg bg-gray-200 text-red-700">
                            Log Out
                        </button>
                    </form>

                    <!-- Submit Password -->
                    <button type="submit"
                        class="px-4 py-3 text-lg font-medium rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function closeIfOutside(event, modalId) {
                const modal = document.getElementById(modalId);
                if (event.target === modal) modal.classList.add("hidden");
            }

            document.getElementById("formGantiPassword").addEventListener("submit", function(e) {
                e.preventDefault();

                let data = {
                    password: document.getElementById("password").value,
                    new_password: document.getElementById("new-password").value,
                };

                fetch("/admin/ganti-password", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(data)
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status) {
                            alert("Password berhasil diganti");
                        } else {
                            alert(res.message || "Gagal memperbarui password");
                        }
                    })
                    .catch(err => console.error(err));
            });
        </script>
    @endpush
@endif
