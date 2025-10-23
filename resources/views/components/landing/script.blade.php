   <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
   <script>
       document.addEventListener('DOMContentLoaded', function() {


           // =========================================================================
           // PENGATURAN NAVBAR UTAMA (MAIN-NAV)
           // =========================================================================
           const mainNavbar = document.getElementById('main-nav');
           const firstSection = document.getElementById('first-section');
           let lastScrollTop = 0;

           const sideNav = document.getElementById('side-nav');
           const landingPageSection = document.getElementById('landing-page');

           if (sideNav && landingPageSection) {
               const sideNavObserverOptions = {
                   root: null,
                   rootMargin: "-80% 0px 0px 0px",
                   threshold: 0,
               };

               const sideNavObserverCallback = function(entries, observer) {
                   entries.forEach(entry => {
                       if (entry.isIntersecting) {
                           sideNav.classList.remove('visible');
                       } else {
                           sideNav.classList.add('visible');
                       }
                   });
               };

               const sideNavObserver = new IntersectionObserver(sideNavObserverCallback, sideNavObserverOptions);
               sideNavObserver.observe(landingPageSection);
           }

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
