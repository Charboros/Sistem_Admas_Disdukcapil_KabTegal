<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <div>
                <h1 class="font-bold text-xl text-slate-800">Dashboard</h1>
                <p class="text-sm text-slate-500 mt-0.5">
                    Selamat datang, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>
                    &mdash; {{ ucfirst(Auth::user()->role) }}
                </p>
            </div>
            {{-- Filter Tahun --}}
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-500">Tahun:</label>
                <select name="tahun" onchange="this.form.submit()"
                        class="border-slate-200 rounded-lg text-sm shadow-sm bg-white
                               focus:border-blue-400 focus:ring-2 focus:ring-blue-100 py-1.5 pr-8">
                    @foreach($daftarTahun as $t)
                        <option value="{{ $t }}" {{ (string)$t == (string)$tahunDipilih ? 'selected' : '' }}>
                            {{ $t }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    {{-- ======================================================= --}}
    {{-- KARTU STATISTIK KESELURUHAN --}}
    {{-- ======================================================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

        {{-- Total Aduan --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                 style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                <x-icons.aduan />
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Total Aduan</p>
                <p class="text-3xl font-bold text-slate-800">{{ $totalAduan }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Semua waktu</p>
            </div>
        </div>

        {{-- Belum Direspon --}}
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                 style="background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);">
                <x-icons.alert />
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Belum Direspon</p>
                <p class="text-3xl font-bold text-red-600">{{ $aduanBelumDirespon }}</p>
                @if($totalAduan > 0)
                    <p class="text-xs text-red-400 mt-0.5">
                        {{ number_format(($aduanBelumDirespon / $totalAduan) * 100, 1) }}% dari total
                    </p>
                @endif
            </div>
        </div>

        {{-- Sudah Direspon --}}
        <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0"
                 style="background: linear-gradient(135deg, #34d399 0%, #059669 100%);">
                <x-icons.check />
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Sudah Direspon</p>
                <p class="text-3xl font-bold text-emerald-600">{{ $aduanSudahDirespon }}</p>
                @if($totalAduan > 0)
                    <p class="text-xs text-emerald-500 mt-0.5">
                        {{ number_format(($aduanSudahDirespon / $totalAduan) * 100, 1) }}% dari total
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress Bar Tingkat Respon --}}
    @if($totalAduan > 0)
        @php $pctRespon = ($aduanSudahDirespon / $totalAduan) * 100; @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-5">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-slate-700">Tingkat Respon Keseluruhan</span>
                <span class="text-sm font-bold {{ $pctRespon >= 80 ? 'text-emerald-600' : 'text-amber-500' }}">
                    {{ number_format($pctRespon, 1) }}%
                </span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="h-3 rounded-full transition-all duration-700
                            {{ $pctRespon >= 80 ? 'bg-emerald-500' : 'bg-amber-400' }}"
                     style="width: {{ $pctRespon }}%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-1.5">
                {{ $aduanSudahDirespon }} dari {{ $totalAduan }} aduan telah direspon
            </p>
        </div>
    @endif

    {{-- ======================================================= --}}
    {{-- STATISTIK TAHUN DIPILIH --}}
    {{-- ======================================================= --}}
    <div class="mb-2">
        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">
            Statistik Tahun {{ $tahunDipilih }}
        </p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <div class="bg-blue-600 rounded-2xl p-4 text-center text-white shadow-sm">
            <p class="text-xs uppercase font-semibold opacity-80">Total Aduan</p>
            <p class="text-3xl font-bold mt-1">{{ $totalTahunIni }}</p>
        </div>
        <div class="bg-emerald-600 rounded-2xl p-4 text-center text-white shadow-sm">
            <p class="text-xs uppercase font-semibold opacity-80">Sudah Direspon</p>
            <p class="text-3xl font-bold mt-1">{{ $sudahDiresponTahunIni }}</p>
        </div>
        <div class="bg-red-500 rounded-2xl p-4 text-center text-white shadow-sm">
            <p class="text-xs uppercase font-semibold opacity-80">Belum Direspon</p>
            <p class="text-3xl font-bold mt-1">{{ $belumDiresponTahunIni }}</p>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- GRAFIK PER BULAN --}}
    {{-- ======================================================= --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-5">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800">Aduan per Bulan &mdash; {{ $tahunDipilih }}</h3>
            @php $totalBulan = array_sum(array_column($dataBulan, 'jumlah')); @endphp
            <span class="text-sm font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-lg">
                Total: {{ $totalBulan }}
            </span>
        </div>
        <div class="p-6">
            @if($totalBulan > 0)
                <div style="height: 200px;" class="mb-5">
                    <canvas id="chartBulan"></canvas>
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            @foreach($dataBulan as $b)
                                <th class="text-center p-2 border border-slate-200 font-semibold text-slate-500 min-w-[40px]">
                                    {{ $b['bulan'] }}
                                </th>
                            @endforeach
                            <th class="text-center p-2 border border-slate-200 font-bold text-blue-700 bg-blue-50 min-w-[50px]">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($dataBulan as $b)
                                <td class="text-center p-2 border border-slate-100
                                           {{ $b['jumlah'] > 0 ? 'font-bold text-blue-700' : 'text-slate-300' }}">
                                    {{ $b['jumlah'] }}
                                </td>
                            @endforeach
                            <td class="text-center p-2 border border-slate-200 font-bold text-blue-800 bg-blue-50">
                                {{ $totalBulan }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- PER KANAL & PER KLASIFIKASI --}}
    {{-- ======================================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        {{-- Per Kanal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Per Kanal &mdash; {{ $tahunDipilih }}</h3>
            </div>
            <div class="p-6">
                @php $totalKanal = $perKanal->sum('jumlah'); @endphp
                @if($totalKanal > 0)
                    <div style="height: 180px;" class="mb-4">
                        <canvas id="chartKanal"></canvas>
                    </div>
                @endif
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-xs text-slate-500">
                            <th class="text-left p-2 border-b border-slate-200 font-semibold">Kanal</th>
                            <th class="text-right p-2 border-b border-slate-200 font-semibold">Jumlah</th>
                            <th class="text-right p-2 border-b border-slate-200 font-semibold">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($perKanal as $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-2 font-medium text-slate-700">{{ $row->kanal }}</td>
                                <td class="p-2 text-right font-bold text-blue-700">{{ $row->jumlah }}</td>
                                <td class="p-2 text-right text-slate-400 text-xs">
                                    {{ $totalKanal > 0 ? number_format(($row->jumlah / $totalKanal) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-slate-400 italic text-sm">
                                    Belum ada data tahun {{ $tahunDipilih }}
                                </td>
                            </tr>
                        @endforelse
                        @if($totalKanal > 0)
                            <tr class="bg-blue-50 font-bold text-sm">
                                <td class="p-2 text-blue-800">Total</td>
                                <td class="p-2 text-right text-blue-800">{{ $totalKanal }}</td>
                                <td class="p-2 text-right text-blue-600">100%</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Per Klasifikasi --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Per Klasifikasi &mdash; {{ $tahunDipilih }}</h3>
            </div>
            <div class="p-6">
                @php $totalKlas = $perKlasifikasi->sum('jumlah'); @endphp
                @if($totalKlas > 0)
                    <div style="height: 180px;" class="mb-4">
                        <canvas id="chartKlasifikasi"></canvas>
                    </div>
                @endif
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-xs text-slate-500">
                            <th class="text-left p-2 border-b border-slate-200 font-semibold">Klasifikasi</th>
                            <th class="text-right p-2 border-b border-slate-200 font-semibold">Jumlah</th>
                            <th class="text-right p-2 border-b border-slate-200 font-semibold">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($perKlasifikasi as $row)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-2 font-medium text-slate-700">{{ $row->klasifikasi }}</td>
                                <td class="p-2 text-right font-bold text-violet-700">{{ $row->jumlah }}</td>
                                <td class="p-2 text-right text-slate-400 text-xs">
                                    {{ $totalKlas > 0 ? number_format(($row->jumlah / $totalKlas) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-slate-400 italic text-sm">
                                    Belum ada data tahun {{ $tahunDipilih }}
                                </td>
                            </tr>
                        @endforelse
                        @if($totalKlas > 0)
                            <tr class="bg-violet-50 font-bold text-sm">
                                <td class="p-2 text-violet-800">Total</td>
                                <td class="p-2 text-right text-violet-800">{{ $totalKlas }}</td>
                                <td class="p-2 text-right text-violet-600">100%</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- TREN TAHUNAN --}}
    {{-- ======================================================= --}}
    @if(count($trenTahunan) > 1)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Tren Aduan Tahunan</h3>
            </div>
            <div class="p-6" style="height: 260px;">
                <canvas id="chartTren"></canvas>
            </div>
        </div>
    @endif

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const palette = [
            '#3B82F6','#8B5CF6','#10B981','#F59E0B',
            '#EF4444','#06B6D4','#EC4899','#84CC16'
        ];

        @if(array_sum(array_column($dataBulan, 'jumlah')) > 0)
        new Chart(document.getElementById('chartBulan'), {
            type: 'bar',
            data: {
                labels: @json(array_column($dataBulan, 'bulan')),
                datasets: [{
                    data: @json(array_column($dataBulan, 'jumlah')),
                    backgroundColor: 'rgba(59,130,246,0.65)',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } } }
            }
        });
        @endif

        @if($perKanal->count() > 0)
        new Chart(document.getElementById('chartKanal'), {
            type: 'doughnut',
            data: {
                labels: @json($perKanal->pluck('kanal')),
                datasets: [{
                    data: @json($perKanal->pluck('jumlah')),
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 10 } } },
                cutout: '60%'
            }
        });
        @endif

        @if($perKlasifikasi->count() > 0)
        new Chart(document.getElementById('chartKlasifikasi'), {
            type: 'doughnut',
            data: {
                labels: @json($perKlasifikasi->pluck('klasifikasi')),
                datasets: [{
                    data: @json($perKlasifikasi->pluck('jumlah')),
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 10 } } },
                cutout: '60%'
            }
        });
        @endif

        @if(count($trenTahunan) > 1)
        new Chart(document.getElementById('chartTren'), {
            type: 'line',
            data: {
                labels: @json(array_column($trenTahunan, 'tahun')),
                datasets: [{
                    label: 'Total Aduan',
                    data: @json(array_column($trenTahunan, 'jumlah')),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 6,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }
                }
            }
        });
        @endif
    </script>
</x-app-layout>
