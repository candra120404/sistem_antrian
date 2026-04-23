<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Antrian Bengkel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-800 to-blue-900 min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md">
    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        {{-- Header Card --}}
        <div class="bg-gradient-to-r from-blue-900 to-blue-700 p-8 text-center">
            <div class="w-16 h-16 bg-white rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-lg">
                <span class="text-3xl">🔧</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Bengkel Digital</h1>
            <p class="text-blue-200 text-sm mt-1">Sistem Informasi Antrian</p>
        </div>

        {{-- Form --}}
        <div class="p-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-6">Masuk ke Akun Anda</h2>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-5 text-sm">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email / Username</label>
                    <input type="text" name="login" value="{{ old('login') }}"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="email@example.com atau username"
                           required autofocus>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password"
                           class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                           placeholder="Masukkan password"
                           required>
                </div>
                <button type="submit"
                        class="w-full bg-blue-900 hover:bg-blue-800 text-white font-semibold py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg">
                    Masuk
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-700 font-semibold hover:underline">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
