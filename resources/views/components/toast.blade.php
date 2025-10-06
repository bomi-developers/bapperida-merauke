 <div id="toast"
     class="fixed top-16 right-2 hidden py-6 px-4 rounded-md shadow-md text-white text-sm font-medium 
           transition-all duration-700 z-[100] w-72 opacity-0 translate-y-2 flex flex-col space-y-2">
     <div class="flex justify-between items-center">
         <span id="toast-icon" class="text-xl"></span>
         <span id="toast-message" class="pt-1"></span>
     </div>
     <div id="toast-progress" class="h-1 bg-white/70 rounded-full w-full scale-x-0 origin-left"></div>
 </div>
 @push('scripts')
     <script>
         let toastTimeout;

         function showToast(message, success = true, duration = 4000) {
             const toast = document.getElementById('toast');
             const toastMessage = document.getElementById('toast-message');
             const toastIcon = document.getElementById('toast-icon');
             const progress = document.getElementById('toast-progress');

             clearTimeout(toastTimeout);

             toastMessage.textContent = message;

             // 🔹 Pilih ikon dan warna background
             const bgColor = success ? 'bg-green-600/85 backdrop-blur-sm' : 'bg-red-600/85 backdrop-blur-sm';
             const icon = success ? ' <i class="bi bi-check-circle"></i>' :
                 '<i class="bi bi-x-circle"></i>';

             toastIcon.innerHTML = icon;

             // 🔹 Set style dasar
             toast.className = `
            fixed top-16 right-2 py-6 px-4 rounded-md shadow-md text-white text-sm font-medium
            ${bgColor}
            transition-all duration-700 z-[100] w-72 opacity-0 translate-y-2 flex flex-col space-y-2
             `;

             toast.classList.remove('hidden');
             requestAnimationFrame(() => {
                 toast.classList.add('animate-fadeIn');
             });

             // 🔹 Reset animasi progress bar
             progress.classList.remove('animate-progress');
             progress.style.animation = 'none';
             progress.offsetHeight; // reflow
             progress.style.setProperty('--toast-duration', `${duration / 5000}s`);
             progress.style.animation = null;
             progress.classList.add('animate-progress');

             // 🔹 Auto hide
             toastTimeout = setTimeout(() => {
                 toast.classList.remove('animate-fadeIn');
                 toast.classList.add('opacity-0', 'translate-y-2');
                 setTimeout(() => {
                     toast.classList.add('hidden');
                     // progress.classList.remove('animate-progress');
                     progress.style.animation = 'none';
                 }, 1000);
             }, duration);
         }
     </script>
 @endpush
