<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Sistem Antrian Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1E3A5F', dark: '#152B46' },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-4 selection:bg-primary selection:text-white">

<div class="w-full max-w-[440px] animate-fade-in-up">
    {{-- Glassmorphism Background Decoration --}}
    <div class="absolute -z-10 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-100 rounded-full blur-[120px] opacity-50"></div>

    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(30,58,95,0.1)] overflow-hidden border border-gray-100">
        {{-- Header Section --}}
        <div class="bg-primary p-10 text-center relative overflow-hidden">
            {{-- Abstract Ornaments --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-12 -mb-12"></div>

            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-3xl shadow-2xl mb-6 transform hover:rotate-6 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tight">Bengkel Digital</h1>
            <p class="text-blue-200/80 text-sm font-medium mt-1 uppercase tracking-widest">Manajemen Antrian</p>
        </div>

        <div class="p-10">
            @if(auth()->check())
                {{-- ── State: Sudah Login ── --}}
                <div class="text-center">
                    <div class="inline-flex items-center justify-center p-2 bg-green-50 rounded-full mb-4">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></div>
                        <span class="text-xs font-bold text-green-700 uppercase tracking-wider">Aktif: {{ auth()->user()->role }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Anda Sedang Login</h2>
                    <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                        Anda saat ini masuk sebagai <span class="font-bold text-primary">{{ auth()->user()->name }}</span>.
                        Gunakan tombol di bawah untuk lanjut atau ganti akun.
                    </p>

                    <div class="space-y-3">
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('pelanggan.dashboard') }}"
                           class="flex items-center justify-center w-full bg-primary hover:bg-primary-dark text-white font-black py-4 rounded-2xl transition-all shadow-lg hover:shadow-primary/30">
                            Masuk ke Dashboard
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-bold py-4 rounded-2xl transition-all flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout & Ganti Akun
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- ── State: Belum Login (Biasa) ── --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-gray-800 tracking-tight">Selamat Datang!</h2>
                    <p class="text-gray-400 text-sm mt-1">Silakan masuk untuk melanjutkan.</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-6 flex gap-3 items-start animate-[shake_0.4s]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-xs text-red-800 font-medium">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form action="/login" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-1.5 cursor-text group" onclick="this.querySelector('input').focus()">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Email / Username</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input type="text" name="login" value="{{ old('login') }}"
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl pl-12 pr-4 py-4 text-sm font-semibold focus:bg-white focus:border-primary focus:outline-none transition-all placeholder:text-gray-300 placeholder:font-normal"
                                   placeholder="Contoh: admin atau user@mail.com" required autofocus>
                        </div>
                    </div>

                    <div class="space-y-1.5 cursor-text group" onclick="this.querySelector('input').focus()">
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input type="password" name="password"
                                   class="w-full bg-gray-50 border-2 border-gray-50 rounded-2xl pl-12 pr-4 py-4 text-sm font-semibold focus:bg-white focus:border-primary focus:outline-none transition-all placeholder:text-gray-300 placeholder:font-normal"
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-xs text-gray-500 group-hover:text-gray-700 transition-colors">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary hover:bg-primary-dark text-white font-black py-4 rounded-2xl transition-all shadow-[0_10px_25px_rgba(30,58,95,0.2)] hover:shadow-[0_15px_35px_rgba(30,58,95,0.35)] active:scale-[0.98] mt-2">
                        Masuk Ke Akun
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-sm text-gray-400 font-medium">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-primary font-black hover:underline underline-offset-4 ml-1">Mulai Daftar</a>
                    </p>
                </div>
            @endif
        </div>
    </div>

    <p class="text-center mt-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em]">
        &copy; {{ date('Y') }} Bengkel Digital Queue System
    </p>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
</style>

</body>
</html>
