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

                <div class="p-5 flex flex-col md:flex-row gap-5 items-start md:items-center">
                    
                    {{-- Thumbnail --}}
                    <div class="shrink-0">
                        @if($aduan->screenshot_path)
                            <img src="{{ asset('storage/' . $aduan->screenshot_path) }}" 
                                 class="w-20 h-20 rounded-xl object-cover border border-slate-200 shadow-sm cursor-zoom-in hover:opacity-90"
                                 onclick="openLightbox('{{ asset('storage/' . $aduan->screenshot_path) }}')">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </div>

                    {{-- Main Info --}}
                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
                        {{-- Info 1 --}}
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor & Kanal</p>
                            <p class="font-mono text-sm font-bold text-blue-700">{{ $aduan->nomor_aduan }}</p>
                            <span class="inline-block px-2 py-0.5 mt-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded text-[11px] font-semibold">
                                {{ $aduan->kanal }}
                            </span>
                        </div>
                        
                        {{-- Info 2 --}}
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Klasifikasi</p>
                            <p class="text-sm font-semibold text-slate-700 leading-snug">{{ $aduan->klasifikasi }}</p>
                        </div>

                        {{-- Info 3 --}}
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pengirim</p>
                            <p class="text-sm font-semibold text-slate-700 truncate" title="{{ $aduan->nama_akun ?? 'Tanpa Nama' }}">{{ $aduan->nama_akun ?? 'Tanpa Nama' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($aduan->tanggal_aduan)->format('d M Y') }}
                                @if($aduan->waktu_aduan) - {{ \Carbon\Carbon::parse($aduan->waktu_aduan)->format('H:i') }} @endif
                            </p>
                        </div>

                        {{-- Info 4 --}}
                        <div class="flex flex-col items-start lg:items-end justify-center gap-2">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 lg:hidden">Status</p>
                            @if($aduan->sudah_direspon)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    Sudah Direspon
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shadow-sm">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                    Belum Direspon
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
                
                {{-- Footer Actions --}}
                <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
                    <p class="text-xs text-slate-500 truncate max-w-[60%] lg:max-w-2xl">
                        <span class="font-semibold text-slate-600">Isi:</span> {{ \Illuminate\Support\Str::limit($aduan->isi_aduan, 120) }}
                    </p>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('aduan.show', $aduan->id) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 hover:text-blue-700 transition shadow-sm flex items-center gap-1.5">
                            Detail / Respon
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        @if(Auth::user()->isAdmin())
                            <form action="{{ route('aduan.destroy', $aduan->id) }}" method="POST" onsubmit="return confirm('Hapus aduan {{ $aduan->nomor_aduan }}?')" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold bg-red-50 border border-red-100 text-red-600 hover:bg-red-100 transition shadow-sm">
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
