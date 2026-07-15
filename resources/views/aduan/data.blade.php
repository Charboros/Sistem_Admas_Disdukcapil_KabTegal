<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-teal-500 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-xl text-white">Data Aduan</h1>
                    <p class="text-sm text-blue-100 mt-0.5">Daftar semua aduan yang telah diinput</p>
                </div>
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
    
                            {{-- Kolom 3: Isi Aduan --}}
                            <div class="flex-[2] flex flex-col gap-1.5 border-r border-slate-100 pr-4">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Isi Aduan</span>
                                <span class="text-xs text-slate-600 leading-relaxed whitespace-pre-wrap">{{ Str::limit($aduan->isi_aduan, 500) }}</span>
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
                                        <x-icons.check-solid class="w-3 h-3" />
                                        Sudah Direspon
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                                        <x-icons.clock-solid class="w-3 h-3" />
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
