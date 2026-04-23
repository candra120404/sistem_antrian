<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Sistem Antrian Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1E3A5F', dark: '#152B46' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-4 selection:bg-primary selection:text-white">

<div class="w-full max-w-[500px] py-10">
    {{-- Background Ball --}}
    <div class="absolute -z-10 bottom-0 right-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-[100px] opacity-60"></div>

    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(30,58,95,0.08)] overflow-hidden border border-gray-100">
        {{-- Progress Bar (Static Ornament) --}}
        <div class="h-1.5 w-full bg-gray-50">
            <div class="h-full w-2/3 bg-primary rounded-r-full"></div>
        </div>

        <div class="p-10">
            @if(auth()->check())
                 {{-- ── State: Sudah Login ── --}}
                 <div class="text-center py-6">
                    <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-800 mb-2">Pendaftaran Ditutup</h2>
                    <p class="text-gray-500 text-sm mb-10 leading-relaxed px-6">
                        Anda sedang login sebagai <span class="font-bold text-primary">{{ auth()->user()->name }}</span>.
                        Silakan logout terlebih dahulu jika ingin membuat akun baru lainnya.
                    </p>

                    <div class="flex flex-col gap-3">
                        <a href="{{ route('pelanggan.dashboard') }}"
                           class="bg-primary hover:bg-primary-dark text-white font-black py-4 rounded-2xl transition-all shadow-lg active:scale-[0.98]">
                            Ke Dashboard Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-red-500 text-sm font-bold py-3 hover:underline">
                                Logout Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- ── State: Form Register ── --}}
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-800 tracking-tight">Buat Akun Baru</h1>
                        <p class="text-gray-400 text-xs font-medium uppercase tracking-widest mt-0.5">Pelanggan Bengkel Digital</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-100 p-4 rounded-xl mb-8">
                        <ul class="text-xs text-red-600 font-bold space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5 group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-4 py-3.5 text-sm font-semibold focus:bg-white focus:border-primary transition-all focus:outline-none"
                                   placeholder="Budi Santoso" required>
                        </div>
                        <div class="space-y-1.5 group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-4 py-3.5 text-sm font-semibold focus:bg-white focus:border-primary transition-all focus:outline-none"
                                   placeholder="budi99" required>
                        </div>
                    </div>

                    <div class="space-y-1.5 group">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-4 py-3.5 text-sm font-semibold focus:bg-white focus:border-primary transition-all focus:outline-none"
                               placeholder="budi@example.com" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5 group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                            <input type="password" name="password"
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-4 py-3.5 text-sm font-semibold focus:bg-white focus:border-primary transition-all focus:outline-none"
                                   placeholder="••••••••" required>
                        </div>
                        <div class="space-y-1.5 group">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Konfirmasi</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl px-4 py-3.5 text-sm font-semibold focus:bg-white focus:border-primary transition-all focus:outline-none"
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                                class="w-full bg-primary hover:bg-primary-dark text-white font-black py-4 rounded-2xl transition-all shadow-[0_10px_25px_rgba(30,58,95,0.2)] active:scale-[0.98]">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-400 font-medium">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-primary font-black hover:underline underline-offset-4 ml-1">Masuk Saja</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>
