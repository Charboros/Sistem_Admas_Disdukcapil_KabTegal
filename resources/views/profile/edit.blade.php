<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-white leading-tight">
                    {{ __('Profil Saya') }}
                </h2>
                <p class="text-sm text-blue-100 mt-0.5">Kelola informasi profil dan kata sandi Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium">
                    {{ session('success') }}
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

            <div class="p-4 sm:p-8 bg-white shadow-sm border border-slate-200 sm:rounded-2xl">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-slate-900">
                                {{ __('Informasi Profil') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ __("Perbarui informasi nama dan kata sandi akun Anda.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Nama Lengkap') }}</label>
                                <input id="name" name="name" type="text" class="w-full border-slate-200 rounded-xl text-sm mt-1 block" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Kata Sandi Baru') }}</label>
                            <div x-data="{ show: false }" class="relative flex items-center mt-1">
                                <input id="password" name="password" :type="show ? 'text' : 'password'" class="w-full border-slate-200 rounded-xl text-sm block pr-10" autocomplete="new-password" placeholder="Biarkan kosong jika tidak ingin mengubah" />
                                <button type="button" @click="show = !show" class="absolute right-3 text-slate-400 hover:text-blue-500 focus:outline-none">
                                    <svg x-show="!show" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">{{ __('Konfirmasi Kata Sandi Baru') }}</label>
                            <div x-data="{ show: false }" class="relative flex items-center mt-1">
                                <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" class="w-full border-slate-200 rounded-xl text-sm block pr-10" autocomplete="new-password" />
                                <button type="button" @click="show = !show" class="absolute right-3 text-slate-400 hover:text-blue-500 focus:outline-none">
                                    <svg x-show="!show" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg x-show="show" x-cloak fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium text-sm transition">
                                    {{ __('Simpan Perubahan') }}
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
