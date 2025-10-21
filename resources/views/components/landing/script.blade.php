   <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
   {{-- <script>
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
   </script> --}}
   <script>
       document.addEventListener('DOMContentLoaded', function() {

           // =========================================================================
           // PENGATURAN NAVIGASI SAMPING (SIDE-NAV)
           // =========================================================================
           const sideNav = document.getElementById('side-nav');
           // Targetnya sekarang adalah landing page, bukan first-section
           const landingPageSection = document.getElementById('landing-page');

           const sideNavObserverOptions = {
               root: null,
               // Trigger saat landing page hampir sepenuhnya hilang dari atas layar
               rootMargin: "-80% 0px 0px 0px",
               threshold: 0,
           };

           const sideNavObserverCallback = function(entries, observer) {
               entries.forEach(entry => {
                   // Logikanya dibalik:
                   // Jika landing page TERLIHAT (intersecting), maka side-nav disembunyikan.
                   if (entry.isIntersecting) {
                       sideNav.classList.remove('visible');
                   }
                   // Jika landing page TIDAK TERLIHAT (sudah di-scroll), maka side-nav ditampilkan.
                   else {
                       sideNav.classList.add('visible');
                   }
               });
           };

           const sideNavObserver = new IntersectionObserver(sideNavObserverCallback, sideNavObserverOptions);

           // Mulai amati landing page
           if (landingPageSection) {
               sideNavObserver.observe(landingPageSection);
           }

           // =========================================================================
           // PENGATURAN NAVBAR UTAMA (MAIN-NAV)
           // =========================================================================
           const mainNavbar = document.getElementById('main-nav');
           const firstSection = document.getElementById('first-section');
           let lastScrollTop = 0;

           function handleScroll() {
               const navHeight = mainNavbar.offsetHeight;
               const currentScrollTop = window.pageYOffset || document.documentElement.scrollTop;

               // Sembunyikan/tampilkan navbar saat scroll
               if (currentScrollTop > lastScrollTop && currentScrollTop > navHeight) {
                   mainNavbar.style.transform = 'translateY(-100%)';
               } else {
                   mainNavbar.style.transform = 'translateY(0)';
               }
               lastScrollTop = currentScrollTop <= 0 ? 0 : currentScrollTop;

               // Ubah warna background navbar
               if (firstSection) {
                   const sectionTop = firstSection.getBoundingClientRect().top;
                   if (sectionTop <= navHeight) {
                       mainNavbar.classList.add('bg-black/30');
                       mainNavbar.classList.remove('bg-white/10');
                   } else {
                       mainNavbar.classList.add('bg-white/10');
                       mainNavbar.classList.remove('bg-black/30');
                   }
               }
           }

           window.addEventListener('scroll', handleScroll);

           // =========================================================================
           // PENGATURAN SEARCH BAR SAMPING
           // =========================================================================
           const toggleButton = document.getElementById('toggle-search-btn');
           const searchBar = document.getElementById('side-search-bar');
           const searchInput = searchBar.querySelector('input');

           toggleButton.addEventListener('click', function(event) {
               event.stopPropagation();
               searchBar.classList.toggle('visible');
               if (searchBar.classList.contains('visible')) {
                   searchInput.focus();
               }
           });

           document.addEventListener('click', function(event) {
               if (!searchBar.contains(event.target) && !toggleButton.contains(event.target)) {
                   searchBar.classList.remove('visible');
               }
           });

           // =========================================================================
           // PENGATURAN ANIMASI SCROLL (REVEAL-ON-SCROLL)
           // =========================================================================
           const revealObserver = new IntersectionObserver(
               (entries) => {
                   entries.forEach((entry) => {
                       if (entry.isIntersecting) {
                           entry.target.classList.add("is-visible");
                       }
                   });
               }, {
                   threshold: 0.1
               }
           );

           const elementsToReveal = document.querySelectorAll(".reveal-on-scroll");
           elementsToReveal.forEach((el) => {
               revealObserver.observe(el);
           });

           // =========================================================================
           // PENGATURAN SLIDER BERITA (SWIPERJS)
           // =========================================================================
           const swiper = new Swiper('.berita-slider', {
               effect: 'coverflow',
               grabCursor: true,
               centeredSlides: true,
               slidesPerView: 'auto',
               loop: true,
               autoplay: {
                   delay: 3500,
                   disableOnInteraction: false,
                   pauseOnMouseEnter: true,
               },
               coverflowEffect: {
                   rotate: -20,
                   stretch: -20,
                   depth: 150,
                   modifier: 1,
                   slideShadows: true,
               },
               pagination: {
                   el: '.swiper-pagination',
                   clickable: true,
               },
           });
       });
   </script>
   @stack('scripts')
