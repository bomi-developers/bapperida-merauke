@component('mail::message')
    # Konfirmasi Pengajuan Proposal Inovasi pada BAPPERIDA Kab. Merauke

    Halo **{{ $proposal->nama }}** 👋,

    Terima kasih telah mengajukan proposal inovasi dengan judul:

    > **{{ $proposal->judul }}**

    Proposal Anda telah berhasil diterima dan sedang dalam proses verifikasi oleh tim kami.

    📎 File Proposal: [Klik untuk melihat]({{ asset('storage/' . $proposal->file) }})

    Terima kasih atas partisipasi Anda dalam mendukung inovasi!

    Salam hangat,
    **Tim BAPPERIDA Kab. Merauke**
@endcomponent
