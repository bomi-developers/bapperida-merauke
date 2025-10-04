 <!-- Modal -->
 <div id="formModal" class="fixed inset-0 hidden bg-black/50 flex items-center justify-center z-50 backdrop-blur">
     <div class="bg-white dark:bg-boxdark p-6 rounded shadow w-1/2 relative">
         <h3 id="modalTitle" class="text-lg font-bold mb-4 dark:text-white">Tambah Bidang</h3>
         <form id="formBidang">
             @csrf
             <input type="hidden" id="bidang_id" name="id">

             <div class="mb-3">
                 <label for="nama_bidang" class="text-sm dark:text-white">Nama Bidang</label>
                 <input type="text" id="nama_bidang" name="nama_bidang"
                     class="w-full px-3 py-2 border rounded dark:border-strokedark 
                               dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none">
             </div>

             <div class="mb-3">
                 <label for="deskripsi" class="text-sm dark:text-white">Deskripsi</label>
                 <textarea id="deskripsi" name="deskripsi"
                     class="w-full px-3 py-2 border rounded dark:border-strokedark 
                               dark:bg-boxdark dark:text-white focus:ring-2 focus:ring-primary outline-none"></textarea>
             </div>

             <div class="flex justify-end gap-2 mt-4">
                 <button type="button" onclick="closeForm()" class="px-3 py-1 bg-danger text-white rounded">
                     Batal
                 </button>
                 <button type="submit" class="px-3 py-1 bg-primary text-white rounded">
                     Simpan
                 </button>
             </div>
         </form>
     </div>
