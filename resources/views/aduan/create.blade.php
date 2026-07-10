<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-xl text-slate-800">Input Aduan</h1>
                <p class="text-sm text-slate-500 mt-0.5">Catat aduan dari masyarakat</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                <p class="text-sm font-semibold text-slate-600">Form Input Aduan Masyarakat</p>
            </div>

            <div class="p-6">
                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                        <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('aduan.store') }}" method="POST"
                      enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Baris 1: Kanal (kiri) & Klasifikasi (kanan) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Kanal --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Kanal Aduan <span class="text-red-500">*</span>
                            </label>
                            <select name="kanal" id="kanal" required
                                    class="w-full border-slate-200 rounded-xl shadow-sm text-sm
                                           focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                                <option value="">— Pilih Kanal —</option>
                                @foreach($listKanal as $k)
                                    <option value="{{ $k }}" {{ old('kanal') == $k ? 'selected' : '' }}>
                                        {{ $k }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Input "Lainnya" untuk Kanal --}}
                            <div id="kanal-lainnya-wrapper" class="mt-2 {{ old('kanal') == 'Lainnya' ? '' : 'hidden' }}">
                                <input type="text" name="kanal_lainnya" id="kanal_lainnya"
                                       value="{{ old('kanal_lainnya') }}"
                                       placeholder="Tuliskan kanal lainnya..."
                                       class="w-full border-slate-200 rounded-xl shadow-sm text-sm
                                              focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>

                        {{-- Klasifikasi --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Klasifikasi <span class="text-red-500">*</span>
                            </label>
                            <select name="klasifikasi" id="klasifikasi" required
                                    class="w-full border-slate-200 rounded-xl shadow-sm text-sm
                                           focus:border-blue-400 focus:ring-2 focus:ring-blue-100 bg-white">
                                <option value="">— Pilih Klasifikasi —</option>
                                @foreach($listKlasifikasi as $kl)
                                    <option value="{{ $kl }}" {{ old('klasifikasi') == $kl ? 'selected' : '' }}>
                                        {{ $kl }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- Input "Lainnya" untuk Klasifikasi --}}
                            <div id="klasifikasi-lainnya-wrapper" class="mt-2 {{ old('klasifikasi') == 'Lainnya' ? '' : 'hidden' }}">
                                <input type="text" name="klasifikasi_lainnya" id="klasifikasi_lainnya"
                                       value="{{ old('klasifikasi_lainnya') }}"
                                       placeholder="Tuliskan spesifikasi lainnya..."
                                       class="w-full border-slate-200 rounded-xl shadow-sm text-sm
                                              focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            </div>
                        </div>
                    </div>

                    {{-- Nama Akun / Pelapor --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-slate-700">Nama Akun / Pelapor</label>
                        <input type="text" name="nama_akun"
                               class="w-full border-slate-200 rounded-xl bg-white focus:ring-blue-500 focus:border-blue-500 transition-colors py-2.5"
                               placeholder="Nama akun sosmed atau nama pelapor"
                               value="{{ old('nama_akun') }}">
                    </div>

                    {{-- Isi Aduan --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Isi Aduan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="isi_aduan" rows="5" required
                                  placeholder="Tuliskan isi aduan atau keterangan (caption) secara lengkap dan jelas..."
                                  class="w-full border-slate-200 rounded-xl shadow-sm text-sm
                                         focus:border-blue-400 focus:ring-2 focus:ring-blue-100">{{ old('isi_aduan') }}</textarea>
                    </div>



                    {{-- Tanggal & Waktu Postingan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Tanggal Postingan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_aduan"
                                   value="{{ old('tanggal_aduan', date('Y-m-d')) }}" required
                                   class="w-full border-slate-200 rounded-xl shadow-sm text-sm
                                          focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                            {{-- Tampilan hari & tanggal --}}
                            <p id="tanggal-display" class="text-xs text-blue-600 mt-1 font-medium"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                                Waktu Postingan
                            </label>
                            <input type="time" name="waktu_aduan"
                                   value="{{ old('waktu_aduan', date('H:i')) }}"
                                   class="w-full border-slate-200 rounded-xl shadow-sm text-sm
                                          focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
                        </div>
                    </div>

                    {{-- Upload Bukti Foto --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Bukti Foto / Screenshot
                        </label>
                        <div class="relative border-2 border-dashed border-slate-200 rounded-xl p-5
                                    hover:border-blue-300 hover:bg-slate-50 transition-colors bg-slate-50/50 cursor-pointer"
                             id="drop-zone">
                            <div class="flex flex-col items-center gap-2 text-center" id="drop-placeholder">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-slate-500">Klik atau seret foto ke sini</p>
                                <p class="text-xs text-slate-400">JPG, PNG, WEBP — maks. 5 MB</p>
                            </div>
                            <img id="preview-img" src="" alt="" class="hidden max-h-48 mx-auto rounded-lg mt-2 shadow-sm">
                        </div>
                        {{-- Input file yang sebenarnya --}}
                        <input type="file" name="screenshot" accept="image/*"
                               id="screenshot-file"
                               class="mt-2 w-full text-sm text-slate-500
                                      file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0
                                      file:bg-blue-50 file:text-blue-700 file:font-medium
                                      hover:file:bg-blue-100 file:transition-colors cursor-pointer">
                    </div>

                    {{-- Tombol Kirim --}}
                    <div class="pt-3 border-t border-slate-100">
                        <button type="submit"
                                class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white rounded-xl
                                       hover:bg-blue-700 font-semibold text-sm transition shadow-sm
                                       flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Kirim Aduan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle Kanal Lainnya
        document.getElementById('kanal')?.addEventListener('change', function () {
            const show = this.value === 'Lainnya';
            const wrapper = document.getElementById('kanal-lainnya-wrapper');
            const input   = document.getElementById('kanal_lainnya');
            wrapper.classList.toggle('hidden', !show);
            if (!show) input.value = '';
        });

        // Toggle Klasifikasi Lainnya
        document.getElementById('klasifikasi')?.addEventListener('change', function () {
            const show = this.value === 'Lainnya';
            const wrapper = document.getElementById('klasifikasi-lainnya-wrapper');
            const input   = document.getElementById('klasifikasi_lainnya');
            wrapper.classList.toggle('hidden', !show);
            if (!show) input.value = '';
        });

        // Tanggal display (hari + tanggal)
        const hariIndo = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const bulanIndo = ['Januari','Februari','Maret','April','Mei','Juni',
                           'Juli','Agustus','September','Oktober','November','Desember'];

        function updateTanggalDisplay() {
            const input = document.querySelector('input[name="tanggal_aduan"]');
            const display = document.getElementById('tanggal-display');
            if (!input || !display || !input.value) return;
            const d = new Date(input.value + 'T00:00:00');
            display.textContent = hariIndo[d.getDay()] + ', ' + d.getDate() + ' '
                + bulanIndo[d.getMonth()] + ' ' + d.getFullYear();
        }
        document.querySelector('input[name="tanggal_aduan"]')?.addEventListener('change', updateTanggalDisplay);
        updateTanggalDisplay();

        // Preview foto
        document.getElementById('screenshot-file')?.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.getElementById('preview-img');
                const placeholder = document.getElementById('drop-placeholder');
                if (img) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                }
                if (placeholder) placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });

        // Drag and drop events
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('screenshot-file');
        
        if(dropZone && fileInput) {
            // Make dropzone clickable
            dropZone.addEventListener('click', () => fileInput.click());

            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-blue-400', 'bg-blue-50');
                dropZone.classList.remove('border-slate-200', 'bg-slate-50/50');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
                dropZone.classList.add('border-slate-200', 'bg-slate-50/50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-blue-400', 'bg-blue-50');
                dropZone.classList.add('border-slate-200', 'bg-slate-50/50');

                if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                    fileInput.files = e.dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });
        }
    </script>
</x-app-layout>
