<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('aduan.data') }}"
                   class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="font-bold text-xl text-slate-800">Detail Aduan #{{ $aduan->id }}</h1>
                </div>
            </div>
            @if($aduan->sudah_direspon)
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                             bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Sudah Direspon
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                             bg-amber-50 text-amber-700 border border-amber-200">
                    Belum Direspon
                </span>
            @endif
        </div>
    </x-slot>

    <div class="flex flex-col gap-5 max-w-4xl mx-auto">

        {{-- ── Bagian Atas: Info Aduan ── --}}
        <div class="space-y-5">

            {{-- Info Utama --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h2 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informasi Aduan
                    </h2>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Tanggal</dt>
                            <dd class="font-semibold text-slate-800">
                                {{ \Carbon\Carbon::parse($aduan->tanggal_aduan)->isoFormat('dddd, D MMMM Y') }}
                            </dd>
                            @if($aduan->waktu_aduan)
                                <dd class="text-sm text-slate-500 mt-0.5">
                                    Pukul {{ \Carbon\Carbon::parse($aduan->waktu_aduan)->format('H:i') }} WIB
                                </dd>
                            @endif
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Petugas Input</dt>
                            <dd class="font-semibold text-slate-800">{{ $aduan->petugas->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Kanal</dt>
                            <dd>
                                <span class="inline-block px-2.5 py-1 bg-indigo-50 text-indigo-700
                                             border border-indigo-200 rounded-lg text-xs font-semibold">
                                    {{ $aduan->kanal }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Klasifikasi</dt>
                            <dd>
                                <span class="inline-block px-2.5 py-1 bg-violet-50 text-violet-700
                                             border border-violet-200 rounded-lg text-xs font-semibold">
                                    {{ $aduan->klasifikasi }}
                                </span>
                            </dd>
                        </div>
                    </div>

                    {{-- Nama Akun --}}
                    @if($aduan->nama_akun)
                        <div class="pt-4 border-t border-slate-100">
                            <dt class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Nama Akun</dt>
                            <dd class="text-sm font-semibold text-indigo-600">{{ $aduan->nama_akun }}</dd>
                        </div>
                    @endif

                    {{-- Isi Aduan --}}
                    <div class="pt-4 border-t border-slate-100">
                        <dt class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-2">Isi Aduan</dt>
                        <dd class="text-slate-700 text-sm leading-relaxed bg-slate-50 p-4 rounded-xl
                                   border border-slate-100 whitespace-pre-wrap">{{ $aduan->isi_aduan }}</dd>
                    </div>

                </div>
            </div>

            {{-- Screenshot --}}
            @if($aduan->screenshot)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h2 class="font-bold text-slate-700 text-sm">Screenshot Aduan</h2>
                    </div>
                    <div class="p-6">
                        <img src="data:image/jpeg;base64,{{ base64_encode($aduan->screenshot) }}"
                             alt="Screenshot"
                             class="max-h-80 rounded-xl border border-slate-200 shadow-sm
                                    cursor-zoom-in hover:opacity-90 transition"
                             onclick="window.open(this.src, '_blank')">
                        <p class="text-xs text-slate-400 mt-2">Klik gambar untuk membuka di tab baru</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Bagian Bawah: Riwayat Respon & Form ── --}}
        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-slate-50 to-blue-50">
                    <h2 class="font-bold text-slate-700 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Riwayat Respon
                    </h2>
                </div>
                <div class="p-6">
                    @if($aduan->respon->count() > 0)
                        <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                            @foreach($aduan->respon as $respon)
                                <div class="p-3 rounded-xl border bg-blue-50 border-blue-200">
                                    <div class="flex justify-between items-start mb-1.5">
                                        <span class="text-xs font-bold text-blue-800">
                                            {{ $respon->user->name ?? '—' }}
                                            <span class="font-normal text-blue-500">
                                                ({{ ucfirst($respon->user->role ?? '') }})
                                            </span>
                                        </span>
                                        <span class="text-xs text-slate-400 whitespace-nowrap ml-2">
                                            {{ \Carbon\Carbon::parse($respon->tanggal_respon)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-700 leading-relaxed">{{ $respon->isi_respon }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center gap-2 py-8 text-slate-400">
                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p class="text-sm italic">Belum ada respon.</p>
                        </div>
                    @endif

                    {{-- Tombol Aksi & Form Tambah Respon --}}
                    <div class="border-t border-slate-100 pt-5 mt-5">
                        
                        {{-- Tombol Awal (Respon & Abaikan) --}}
                        <div id="action-buttons" class="flex gap-3">
                            <button type="button" onclick="showResponForm()" 
                                    class="flex-1 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition shadow-sm text-sm">
                                Respon
                            </button>
                            <a href="{{ route('aduan.data') }}" 
                               class="flex-1 py-3 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition text-center shadow-sm border border-slate-200 text-sm">
                                Abaikan
                            </a>
                        </div>

                        {{-- Form Respon (Tersembunyi Awalnya) --}}
                        <div id="respon-form-container" class="hidden">
                            <form action="{{ route('aduan.respon.store', $aduan->id) }}" method="POST">
                                @csrf
                                <label class="block text-sm font-bold text-slate-700 mb-2">Tulis Respon</label>
                                <textarea name="isi_respon" id="respon-textarea" rows="4" required
                                          placeholder="Tulis tanggapan atau tindak lanjut..."
                                          class="w-full border-slate-300 rounded-xl text-sm mb-4
                                                 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 bg-slate-50"></textarea>
                                
                                <div class="flex gap-3">
                                    <button type="submit"
                                            class="flex-1 flex items-center justify-center gap-2 px-4 py-3
                                                   bg-blue-600 text-white rounded-xl text-sm font-bold
                                                   hover:bg-blue-700 transition shadow-sm">
                                        Kirim
                                    </button>
                                    <a href="{{ route('aduan.data') }}"
                                       class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold
                                              hover:bg-slate-200 transition text-center border border-slate-200">
                                        Abaikan
                                    </a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function showResponForm() {
            document.getElementById('action-buttons').classList.add('hidden');
            document.getElementById('respon-form-container').classList.remove('hidden');
            document.getElementById('respon-textarea').focus();
        }
    </script>
</x-app-layout>
