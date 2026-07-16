<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h1 class="font-bold text-xl text-white">Konfigurasi</h1>
                <p class="text-sm text-blue-100 mt-0.5">Kelola User, Kanal, dan Klasifikasi Aduan</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- Pesan Sukses / Error --}}
        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm">
                <p class="font-semibold mb-1">Terdapat kesalahan:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- TABS NAVIGATION --}}
        <div class="border-b border-slate-200">
            <nav class="flex gap-4" aria-label="Tabs">
                <button onclick="switchTab('tab-user', this)" class="tab-btn shrink-0 border-b-2 px-1 pb-4 text-sm font-medium border-indigo-500 text-indigo-600 active">
                    Manajemen User
                </button>
                <button onclick="switchTab('tab-kategori', this)" class="tab-btn shrink-0 border-b-2 border-transparent px-1 pb-4 text-sm font-medium text-slate-500 hover:border-slate-300 hover:text-slate-700">
                    Kanal & Klasifikasi
                </button>
            </nav>
        </div>

        {{-- TAB CONTENT: USER --}}
        <div id="tab-user" class="tab-content block">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col lg:flex-row">
                
                {{-- Form Tambah User --}}
                <div class="p-6 border-b lg:border-b-0 lg:border-r border-slate-100 bg-slate-50/50 lg:w-1/3">
                    <h2 class="text-sm font-semibold text-slate-700 mb-4">Tambah User Baru</h2>
                    <form action="{{ route('konfigurasi.user.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama User</label>
                            <input type="text" name="name" required class="w-full border-slate-200 rounded-xl text-sm" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                            <select name="role" required class="w-full border-slate-200 rounded-xl text-sm bg-white">
                                <option value="petugas">Petugas</option>
                                <option value="pimpinan">Pimpinan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                            <div x-data="{ show: false }" class="relative flex items-center">
                                <input :type="show ? 'text' : 'password'" name="password" required class="w-full border-slate-200 rounded-xl text-sm pr-10">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-500 focus:outline-none">
                                    <svg x-show="!show" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                            <div x-data="{ show: false }" class="relative flex items-center">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" required class="w-full border-slate-200 rounded-xl text-sm pr-10">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-500 focus:outline-none">
                                    <svg x-show="!show" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium text-sm transition">
                                Tambah User
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Tabel Daftar User --}}
                <div class="p-0 overflow-x-auto lg:w-2/3 flex flex-col">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($users as $u)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-800">{{ $u->name }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded text-xs font-medium uppercase tracking-wider">
                                        {{ $u->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex justify-end gap-3">
                                        <button type="button" onclick="editUser({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ $u->role }}')" class="text-blue-500 hover:text-blue-700 font-medium text-sm">Edit</button>
                                        <form action="{{ route('konfigurasi.user.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700 font-medium text-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-slate-500">Belum ada user.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB CONTENT: KANAL & KLASIFIKASI --}}
        <div id="tab-kategori" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- MANAJEMEN KANAL --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-teal-50 to-emerald-50 flex justify-between items-center">
                        <p class="text-sm font-semibold text-slate-700">Manajemen Kanal</p>
                        <button onclick="openModal('modal-merge-kanal')" class="text-xs px-3 py-1.5 bg-white text-teal-700 border border-teal-200 rounded-lg hover:bg-teal-50 font-medium transition">
                            Gabungkan Data (Merge)
                        </button>
                    </div>
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                        <form action="{{ route('konfigurasi.kanal.store') }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="text" name="nama" required class="flex-1 border-slate-200 rounded-xl text-sm" placeholder="Nama Kanal Baru">
                            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 font-medium text-sm transition">
                                Tambah
                            </button>
                        </form>
                    </div>
                    <div class="p-0 overflow-y-auto max-h-96">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($kanals as $kanal)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-3 font-medium text-slate-800">{{ $kanal->nama }}</td>
                                    <td class="px-6 py-3 text-right">
                                        @if(strtolower($kanal->nama) !== 'lainnya')
                                        <form action="{{ route('konfigurasi.kanal.destroy', $kanal->id) }}" method="POST" onsubmit="return confirm('Hapus kanal ini?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700 font-medium text-sm">Hapus</button>
                                        </form>
                                        @else
                                        <span class="text-xs text-slate-400 italic">Bawaan Sistem</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-slate-500">Belum ada kanal.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- MANAJEMEN KLASIFIKASI --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-orange-50 to-amber-50 flex justify-between items-center">
                        <p class="text-sm font-semibold text-slate-700">Manajemen Klasifikasi</p>
                        <button onclick="openModal('modal-merge-klasifikasi')" class="text-xs px-3 py-1.5 bg-white text-orange-700 border border-orange-200 rounded-lg hover:bg-orange-50 font-medium transition">
                            Gabungkan Data (Merge)
                        </button>
                    </div>
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                        <form action="{{ route('konfigurasi.klasifikasi.store') }}" method="POST" class="flex gap-3">
                            @csrf
                            <input type="text" name="nama" required class="flex-1 border-slate-200 rounded-xl text-sm" placeholder="Nama Klasifikasi Baru">
                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-xl hover:bg-orange-700 font-medium text-sm transition">
                                Tambah
                            </button>
                        </form>
                    </div>
                    <div class="p-0 overflow-y-auto max-h-96">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-slate-100">
                                @forelse($klasifikasis as $klasifikasi)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-3 font-medium text-slate-800">{{ $klasifikasi->nama }}</td>
                                    <td class="px-6 py-3 text-right">
                                        @if(strtolower($klasifikasi->nama) !== 'lainnya')
                                        <form action="{{ route('konfigurasi.klasifikasi.destroy', $klasifikasi->id) }}" method="POST" onsubmit="return confirm('Hapus klasifikasi ini?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-700 font-medium text-sm">Hapus</button>
                                        </form>
                                        @else
                                        <span class="text-xs text-slate-400 italic">Bawaan Sistem</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-center text-slate-500">Belum ada klasifikasi.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL MERGE KANAL --}}
    <div id="modal-merge-kanal" class="fixed inset-0 bg-slate-900/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Gabungkan Kanal</h3>
                <button onclick="closeModal('modal-merge-kanal')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-500 mb-4">Pindahkan semua aduan dari satu kanal ke kanal lainnya.</p>
                <form action="{{ route('konfigurasi.kanal.merge') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kanal Asal (Data Lama)</label>
                        <select name="kanal_asal" required class="w-full border-slate-200 rounded-xl text-sm bg-white">
                            <option value="">— Pilih Kanal Asal —</option>
                            @foreach($allKanalsAsal as $ak)
                                <option value="{{ $ak }}">{{ $ak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-center text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Kanal Tujuan (Digabung Ke)</label>
                        <select name="kanal_tujuan" required class="w-full border-slate-200 rounded-xl text-sm bg-white">
                            <option value="">— Pilih Kanal Tujuan —</option>
                            @foreach($kanals as $kanal)
                                <option value="{{ $kanal->nama }}">{{ $kanal->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal('modal-merge-kanal')" class="flex-1 px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 font-medium text-sm transition">Batal</button>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menggabungkan data ini?')" class="flex-1 px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 font-medium text-sm transition">Gabungkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL MERGE KLASIFIKASI --}}
    <div id="modal-merge-klasifikasi" class="fixed inset-0 bg-slate-900/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Gabungkan Klasifikasi</h3>
                <button onclick="closeModal('modal-merge-klasifikasi')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <p class="text-sm text-slate-500 mb-4">Pindahkan semua aduan dari satu klasifikasi ke klasifikasi lainnya.</p>
                <form action="{{ route('konfigurasi.klasifikasi.merge') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Klasifikasi Asal (Data Lama)</label>
                        <select name="klasifikasi_asal" required class="w-full border-slate-200 rounded-xl text-sm bg-white">
                            <option value="">— Pilih Klasifikasi Asal —</option>
                            @foreach($allKlasifikasisAsal as $aklas)
                                <option value="{{ $aklas }}">{{ $aklas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-center text-slate-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Klasifikasi Tujuan (Digabung Ke)</label>
                        <select name="klasifikasi_tujuan" required class="w-full border-slate-200 rounded-xl text-sm bg-white">
                            <option value="">— Pilih Klasifikasi Tujuan —</option>
                            @foreach($klasifikasis as $klas)
                                <option value="{{ $klas->nama }}">{{ $klas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal('modal-merge-klasifikasi')" class="flex-1 px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 font-medium text-sm transition">Batal</button>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menggabungkan data ini?')" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-xl hover:bg-orange-700 font-medium text-sm transition">Gabungkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT USER --}}
    <div id="modal-edit-user" class="fixed inset-0 bg-slate-900/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Edit User</h3>
                <button onclick="closeModal('modal-edit-user')" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6">
                <form id="form-edit-user" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama User</label>
                        <input type="text" name="name" id="edit-user-name" required class="w-full border-slate-200 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Role</label>
                        <select name="role" id="edit-user-role" required class="w-full border-slate-200 rounded-xl text-sm bg-white">
                            <option value="petugas">Petugas</option>
                            <option value="pimpinan">Pimpinan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru (Opsional)</label>
                        <div x-data="{ show: false }" class="relative flex items-center">
                            <input :type="show ? 'text' : 'password'" name="password" class="w-full border-slate-200 rounded-xl text-sm pr-10" placeholder="Kosongkan jika tidak ingin diubah">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-500 focus:outline-none">
                                <svg x-show="!show" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                        <div x-data="{ show: false }" class="relative flex items-center">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" class="w-full border-slate-200 rounded-xl text-sm pr-10">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-500 focus:outline-none">
                                <svg x-show="!show" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="pt-4 flex gap-3">
                        <button type="button" onclick="closeModal('modal-edit-user')" class="flex-1 px-4 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 font-medium text-sm transition">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-medium text-sm transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editUser(id, name, role) {
            document.getElementById('edit-user-name').value = name;
            document.getElementById('edit-user-role').value = role;
            document.getElementById('form-edit-user').action = `/konfigurasi/user/${id}`;
            openModal('modal-edit-user');
        }
        function switchTab(tabId, btn) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            // Reset all buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('border-indigo-500', 'text-indigo-600', 'active');
                el.classList.add('border-transparent', 'text-slate-500');
            });
            
            // Show target tab
            document.getElementById(tabId).classList.remove('hidden');
            document.getElementById(tabId).classList.add('block');
            
            // Highlight button
            btn.classList.add('border-indigo-500', 'text-indigo-600', 'active');
            btn.classList.remove('border-transparent', 'text-slate-500');
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>
