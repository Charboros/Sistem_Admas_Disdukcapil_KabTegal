<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h1 class="font-bold text-xl text-slate-800">Data Aduan</h1>
                <p class="text-sm text-slate-500 mt-0.5">Daftar semua aduan yang telah diinput</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- Info/Stats ringkas bisa ditaruh di sini jika perlu --}}
            </div>
        </div>
    </x-slot>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
        <form action="{{ route('aduan.data') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px] sm:max-w-xs">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" id="searchInput" placeholder="Cari cepat di halaman ini..."
                       class="pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition w-full">
            </div>

            <select name="status" class="py-2 pl-3 pr-8 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-200 outline-none">
                <option value="">Semua Status</option>
                <option value="sudah" {{ request('status') === 'sudah' ? 'selected' : '' }}>Sudah Direspon</option>
                <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum Direspon</option>
            </select>

            <select name="kanal" class="py-2 pl-3 pr-8 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-200 outline-none">
                <option value="">Semua Kanal</option>
                @foreach($listKanal as $kanal)
                    <option value="{{ $kanal }}" {{ request('kanal') === $kanal ? 'selected' : '' }}>{{ $kanal }}</option>
                @endforeach
            </select>

            <select name="tahun" class="py-2 pl-3 pr-8 text-sm border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-200 outline-none">
                <option value="">Semua Tahun</option>
                @foreach($listTahun as $tahun)
                    <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                Filter
            </button>
            <a href="{{ route('aduan.data') }}" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-300 transition shadow-sm">
                Reset
            </a>
        </form>
    </div>

    <div class="space-y-4" id="aduan-list">
        @forelse($aduans as $aduan)
            <div class="aduan-card bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden
                        transition-all duration-200 hover:shadow-md hover:border-blue-200"
                 data-id="{{ $aduan->id }}">

                {{-- Card Layout 5 Kolom Horizontal Sesuai Permintaan --}}
                <div class="p-5">
                    <div class="w-full overflow-x-auto pb-2">
                        <div class="flex items-start justify-between gap-6 min-w-[800px]">
                            
                            {{-- Kolom 1: Kanal --}}
                            <div class="flex-1 flex flex-col gap-1.5 border-r border-slate-100 pr-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kanal</span>
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-700 w-max">
                                    @if($aduan->kanal === 'Instagram')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    @elseif($aduan->kanal === 'Facebook')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    @elseif(str_contains($aduan->kanal, 'Gmaps'))
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.052 0 4.5 3.552 4.5 7.5c0 5.523 7.5 16.5 7.5 16.5s7.5-10.977 7.5-16.5C19.5 3.552 15.948 0 12 0zm0 10.5c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/></svg>
                                    @endif
                                    {{ $aduan->kanal }}
                                </span>
                                <span class="font-mono text-xs font-bold text-blue-600 mt-1">#{{ $aduan->id }}</span>
                            </div>
    
                            {{-- Kolom 2: Klasifikasi --}}
                            <div class="flex-1 flex flex-col gap-1.5 border-r border-slate-100 pr-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Klasifikasi</span>
                                <span class="text-xs font-semibold text-slate-700 leading-snug">
                                    {{ $aduan->klasifikasi }}
                                </span>
                            </div>
    
                            <div class="flex-none w-32 flex flex-col gap-1.5 border-r border-slate-100 pr-4 items-center text-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Gambar</span>
                                @if($aduan->screenshot)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($aduan->screenshot) }}" 
                                         alt="Screenshot" 
                                         class="w-12 h-12 object-cover rounded shadow-sm border border-slate-200 cursor-zoom-in hover:opacity-90"
                                         onclick="openLightbox('data:image/jpeg;base64,{{ base64_encode($aduan->screenshot) }}')"
                                         title="Klik jika ingin lihat full">
                                @else
                                    <span class="text-[10px] text-slate-400 italic mt-2">Tidak ada foto</span>
                                @endif
                            </div>
    
                            {{-- Kolom 4: Waktu Postingan --}}
                            <div class="flex-1 flex flex-col gap-1.5 border-r border-slate-100 pr-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu Postingan</span>
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($aduan->tanggal_aduan)->isoFormat('D MMM Y') }}
                                </span>
                                @if($aduan->waktu_aduan)
                                    <span class="text-[11px] text-slate-500">
                                        {{ \Carbon\Carbon::parse($aduan->waktu_aduan)->format('H:i') }} WIB
                                    </span>
                                @endif
                                <span class="text-[11px] font-medium text-indigo-600 truncate mt-1" title="{{ $aduan->nama_akun }}">
                                    {{ $aduan->nama_akun ?? 'Tanpa Nama' }}
                                </span>
                            </div>
    
                            {{-- Kolom 5: Status Respon --}}
                            <div class="flex-1 flex flex-col gap-2 items-start">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status</span>
                                @if($aduan->sudah_direspon)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Sudah Direspon
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        Belum Direspon
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- Tombol Detail & Aksi (Full Width) --}}
                    <div class="mt-4 pt-4 border-t border-slate-100 flex gap-3">
                        <a href="{{ route('aduan.show', $aduan->id) }}" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-bold bg-slate-800 text-white hover:bg-slate-700 transition flex items-center justify-center gap-2 shadow-sm text-center">
                            Detail Aduan & Respon
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        @if(Auth::user()->isAdmin())
                            <form action="{{ route('aduan.destroy', $aduan->id) }}" method="POST" onsubmit="return confirm('Hapus aduan #{{ $aduan->id }}?')" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-red-50 text-red-600 hover:bg-red-100 transition border border-red-100 flex items-center justify-center h-full">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            </div>{{-- end .aduan-card --}}
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center">
                <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p class="font-semibold text-slate-500">Belum ada data aduan</p>
                <p class="text-sm text-slate-400 mt-1">
                    <a href="{{ route('aduan.create') }}" class="text-blue-600 hover:underline">Input aduan pertama</a>
                </p>
            </div>
        @endforelse
    </div>

    {{-- Footer count --}}
    @if($aduans->count() > 0)
        <div class="mt-4 text-xs text-slate-400 text-center">
            Menampilkan <span id="visible-count" class="font-semibold text-slate-600">{{ $aduans->count() }}</span>
            dari {{ $aduans->count() }} aduan
        </div>
    @endif

    {{-- Lightbox --}}
    <div id="lightbox" onclick="closeLightbox()"
         class="fixed inset-0 bg-black/75 z-50 hidden items-center justify-center p-4 cursor-zoom-out">
        <img id="lightbox-img" src="" alt="Preview" class="max-w-full max-h-full rounded-xl shadow-2xl">
    </div>

    <script>
        // Lightbox
        function openLightbox(src) {
            const lb = document.getElementById('lightbox');
            document.getElementById('lightbox-img').src = src;
            lb.classList.remove('hidden');
            lb.classList.add('flex');
        }
        function closeLightbox() {
            const lb = document.getElementById('lightbox');
            lb.classList.add('hidden');
            lb.classList.remove('flex');
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

        // Search
        const searchInput   = document.getElementById('searchInput');
        const cards         = document.querySelectorAll('.aduan-card');
        const visibleCount  = document.getElementById('visible-count');

        searchInput?.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            let count = 0;
            cards.forEach(card => {
                const match = !q || card.textContent.toLowerCase().includes(q);
                card.style.display = match ? '' : 'none';
                if (match) count++;
            });
            if (visibleCount) visibleCount.textContent = count;
        });
    </script>
</x-app-layout>
