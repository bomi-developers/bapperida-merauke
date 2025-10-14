   <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
   <script>
       // Menunggu hingga seluruh konten halaman dimuat
       document.addEventListener('DOMContentLoaded', function() {

           // Pilih elemen navbar dan section pertama berdasarkan id-nya
           const navbar = document.getElementById('main-nav');
           const firstSection = document.getElementById('first-section');

           // Fungsi yang akan dijalankan setiap kali pengguna scroll
           function changeNavBackground() {
               // Dapatkan posisi atas section pertama relatif terhadap layar
               const sectionTop = firstSection.getBoundingClientRect().top;

               // Dapatkan tinggi dari navbar
               const navHeight = navbar.offsetHeight;

               // Cek jika bagian atas section sudah berada di bawah atau sejajar dengan navbar
               if (sectionTop <= navHeight) {
                   // Jika iya, ganti warna background
                   navbar.classList.add('bg-black/30');
                   navbar.classList.remove('bg-white/10');
               } else {
                   // Jika tidak (masih di bagian atas), kembalikan ke warna semula
                   navbar.classList.add('bg-white/10');
                   navbar.classList.remove('bg-black/30');
               }
           }

           // Tambahkan event listener yang memanggil fungsi di atas saat scroll
           window.addEventListener('scroll', changeNavBackground);
       });
       document.addEventListener("DOMContentLoaded", function() {
           const observer = new IntersectionObserver(
               (entries) => {
                   entries.forEach((entry) => {
                       // Jika elemen masuk ke layar, tambahkan class 'is-visible'
                       if (entry.isIntersecting) {
                           entry.target.classList.add("is-visible");
                       }
                   });
               }, {
                   threshold: 0.1,
               }
           );

           // Amati semua elemen dengan class 'reveal-on-scroll'
           const elementsToReveal = document.querySelectorAll(".reveal-on-scroll");
           elementsToReveal.forEach((el) => {
               observer.observe(el);
           });

           // Inisialisasi SwiperJS dengan efek 3D Coverflow
           const swiper = new Swiper('.berita-slider', {
               effect: 'coverflow',
               grabCursor: true,
               centeredSlides: true,
               slidesPerView: 'auto',
               loop: true,
               autoplay: {
                   delay: 3500,
                   disableOnInteraction: false,
                   pauseOnMouseEnter: true, // Berhenti saat kursor di atas slider
               },
               coverflowEffect: {
                   rotate: -20, // Kemiringan slide samping
                   stretch: -20, // Jarak antar slide (negatif agar lebih rapat)
                   depth: 150, // Efek kedalaman 3D
                   modifier: 1, // Pengali efek (1=normal)
                   slideShadows: true, // Aktifkan bayangan slide
               },
               pagination: {
                   el: '.swiper-pagination',
                   clickable: true,
               },
           });
       });
   </script>
   @stack('scripts')
