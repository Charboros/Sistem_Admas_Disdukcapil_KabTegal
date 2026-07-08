<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <div>
                <h2 class="font-bold text-xl text-gray-800">Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">Selamat datang, {{ Auth::user()->name }} &mdash; {{ ucfirst(Auth::user()->role) }}</p>
            </div>
            {{-- Filter Tahun --}}
            <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-600">Tahun:</label>
                <select name="tahun" onchange="this.form.submit()"
                    class="border-gray-300 rounded-md text-sm shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5">
                    @foreach($daftarTahun as $t)
                        <option value="{{ $t }}" {{ (string)$t == (string)$tahunDipilih ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    {{-- ============================================================ --}}
    {{-- BAGIAN 1: KARTU STATISTIK KESELURUHAN --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
        {{-- Total Aduan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-xl shrink-0">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Total Aduan</p>
                <p class="text-3xl font-bold text-blue-700">{{ $totalAduan }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Semua waktu</p>
            </div>
        </div>
        {{-- Belum Direspon --}}
        <div class="bg-white rounded-xl shadow-sm border border-red-100 p-5 flex items-center gap-4">
            <div class="bg-red-100 p-3 rounded-xl shrink-0">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Belum Direspon</p>
                <p class="text-3xl font-bold text-red-600">{{ $aduanBelumDirespon }}</p>
                @if($totalAduan > 0)
                    <p class="text-xs text-red-400 mt-0.5">{{ number_format(($aduanBelumDirespon/$totalAduan)*100,1) }}% dari total</p>
                @endif
            </div>
        </div>
        {{-- Sudah Direspon --}}
        <div class="bg-white rounded-xl shadow-sm border border-green-100 p-5 flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-xl shrink-0">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide">Sudah Direspon</p>
                <p class="text-3xl font-bold text-green-600">{{ $aduanSudahDirespon }}</p>
                @if($totalAduan > 0)
                    <p class="text-xs text-green-500 mt-0.5">{{ number_format(($aduanSudahDirespon/$totalAduan)*100,1) }}% dari total</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Progress Bar Tingkat Respon --}}
    @if($totalAduan > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-semibold text-gray-700">Tingkat Respon Keseluruhan</span>
            <span class="text-sm font-bold {{ $aduanSudahDirespon/$totalAduan >= 0.8 ? 'text-green-600' : 'text-orange-500' }}">
                {{ number_format(($aduanSudahDirespon/$totalAduan)*100, 1) }}%
            </span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full transition-all duration-700 {{ $aduanSudahDirespon/$totalAduan >= 0.8 ? 'bg-green-500' : 'bg-orange-400' }}"
                 style="width: {{ ($aduanSudahDirespon/$totalAduan)*100 }}%"></div>
        </div>
        <p class="text-xs text-gray-400 mt-1.5">{{ $aduanSudahDirespon }} dari {{ $totalAduan }} aduan telah direspon</p>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- BAGIAN 2: STATISTIK TAHUN INI --}}
    {{-- ============================================================ --}}
    <div class="mb-2">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Statistik Tahun {{ $tahunDipilih }}</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-center">
            <p class="text-xs text-blue-500 uppercase font-semibold">Total Aduan</p>
            <p class="text-3xl font-bold text-blue-700 mt-1">{{ $totalTahunIni }}</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-xs text-green-600 uppercase font-semibold">Sudah Direspon</p>
            <p class="text-3xl font-bold text-green-700 mt-1">{{ $sudahDiresponTahunIni }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
            <p class="text-xs text-red-500 uppercase font-semibold">Belum Direspon</p>
            <p class="text-3xl font-bold text-red-700 mt-1">{{ $belumDiresponTahunIni }}</p>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BAGIAN 3: GRAFIK PER BULAN --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Aduan per Bulan — {{ $tahunDipilih }}</h3>
            @php $totalBulan = array_sum(array_column($dataBulan, 'jumlah')); @endphp
            <span class="text-sm font-semibold text-blue-600">Total: {{ $totalBulan }}</span>
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
                        <tr class="bg-gray-50">
                            @foreach($dataBulan as $b)
                                <th class="text-center p-2 border border-gray-200 font-semibold text-gray-500 min-w-[40px]">{{ $b['bulan'] }}</th>
                            @endforeach
                            <th class="text-center p-2 border border-gray-200 font-bold text-blue-700 bg-blue-50 min-w-[50px]">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($dataBulan as $b)
                                <td class="text-center p-2 border border-gray-100 {{ $b['jumlah'] > 0 ? 'font-bold text-blue-700' : 'text-gray-300' }}">
                                    {{ $b['jumlah'] }}
                                </td>
                            @endforeach
                            <td class="text-center p-2 border border-gray-200 font-bold text-blue-800 bg-blue-50">{{ $totalBulan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BAGIAN 4: KANAL & KLASIFIKASI (2 KOLOM) --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

        {{-- Per Kanal --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Per Kanal — {{ $tahunDipilih }}</h3>
            </div>
            <div class="p-6">
                @php $totalKanal = $perKanal->sum('jumlah'); @endphp
                @if($totalKanal > 0)
                    <div style="height: 180px;" class="mb-4">
                        <canvas id="chartKanal"></canvas>
                    </div>
                @endif
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500">
                            <th class="text-left p-2 border-b">Kanal</th>
                            <th class="text-right p-2 border-b">Jumlah</th>
                            <th class="text-right p-2 border-b">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perKanal as $row)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="p-2 font-medium text-gray-700">{{ $row->kanal }}</td>
                            <td class="p-2 text-right font-bold text-blue-700">{{ $row->jumlah }}</td>
                            <td class="p-2 text-right text-gray-400 text-xs">
                                {{ $totalKanal > 0 ? number_format(($row->jumlah/$totalKanal)*100, 1) : 0 }}%
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-400 italic text-sm">Belum ada data tahun {{ $tahunDipilih }}</td></tr>
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-bold text-gray-800">Per Klasifikasi — {{ $tahunDipilih }}</h3>
            </div>
            <div class="p-6">
                @php $totalKlas = $perKlasifikasi->sum('jumlah'); @endphp
                @if($totalKlas > 0)
                    <div style="height: 180px;" class="mb-4">
                        <canvas id="chartKlasifikasi"></canvas>
                    </div>
                @endif
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-xs text-gray-500">
                            <th class="text-left p-2 border-b">Klasifikasi</th>
                            <th class="text-right p-2 border-b">Jumlah</th>
                            <th class="text-right p-2 border-b">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($perKlasifikasi as $row)
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="p-2 font-medium text-gray-700">{{ $row->klasifikasi }}</td>
                            <td class="p-2 text-right font-bold text-purple-700">{{ $row->jumlah }}</td>
                            <td class="p-2 text-right text-gray-400 text-xs">
                                {{ $totalKlas > 0 ? number_format(($row->jumlah/$totalKlas)*100, 1) : 0 }}%
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="p-4 text-center text-gray-400 italic text-sm">Belum ada data tahun {{ $tahunDipilih }}</td></tr>
                        @endforelse
                        @if($totalKlas > 0)
                        <tr class="bg-purple-50 font-bold text-sm">
                            <td class="p-2 text-purple-800">Total</td>
                            <td class="p-2 text-right text-purple-800">{{ $totalKlas }}</td>
                            <td class="p-2 text-right text-purple-600">100%</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- BAGIAN 5: TREN TAHUNAN (jika > 1 tahun) --}}
    {{-- ============================================================ --}}
    @if($trenTahunan->count() > 1)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Tren Aduan Tahunan</h3>
        </div>
        <div class="p-6" style="height: 240px;">
            <canvas id="chartTren"></canvas>
        </div>
    </div>
    @endif

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const palette = ['#3B82F6','#8B5CF6','#10B981','#F59E0B','#EF4444','#06B6D4','#EC4899','#84CC16'];

        @if(array_sum(array_column($dataBulan, 'jumlah')) > 0)
        new Chart(document.getElementById('chartBulan'), {
            type: 'bar',
            data: {
                labels: @json(array_column($dataBulan, 'bulan')),
                datasets: [{
                    data: @json(array_column($dataBulan, 'jumlah')),
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: '#3B82F6',
                    borderWidth: 1,
                    borderRadius: 5,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        @endif

        @if($perKanal->count() > 0)
        new Chart(document.getElementById('chartKanal'), {
            type: 'doughnut',
            data: {
                labels: @json($perKanal->pluck('kanal')),
                datasets: [{ data: @json($perKanal->pluck('jumlah')), backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } } },
                cutout: '58%'
            }
        });
        @endif

        @if($perKlasifikasi->count() > 0)
        new Chart(document.getElementById('chartKlasifikasi'), {
            type: 'doughnut',
            data: {
                labels: @json($perKlasifikasi->pluck('klasifikasi')),
                datasets: [{ data: @json($perKlasifikasi->pluck('jumlah')), backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { font: { size: 10 }, padding: 8 } } },
                cutout: '58%'
            }
        });
        @endif

        @if($trenTahunan->count() > 1)
        new Chart(document.getElementById('chartTren'), {
            type: 'line',
            data: {
                labels: @json($trenTahunan->pluck('tahun')),
                datasets: [{
                    label: 'Total Aduan',
                    data: @json($trenTahunan->pluck('jumlah')),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    fill: true, tension: 0.3,
                    pointRadius: 6, pointBackgroundColor: '#3B82F6',
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
        @endif
    </script>
</x-app-layout>
