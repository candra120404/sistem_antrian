<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuthController — mengelola autentikasi berbasis session (web).
 * Login menggunakan email atau username. Redirect sesuai role setelah login.
 */
class AuthController extends Controller
{
    /** Tampilkan form login. */
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    /** Proses login, validasi kredensial, redirect sesuai role. */
    public function login(LoginRequest $request)
    {
        $loginField = $request->login;

        // Coba login dengan email atau username
        $credentials = filter_var($loginField, FILTER_VALIDATE_EMAIL)
            ? ['email' => $loginField, 'password' => $request->password]
            : ['username' => $loginField, 'password' => $request->password];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'Email/username atau password salah.']);
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
    }

    /** Tampilkan form registrasi pelanggan. */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.register');
    }

    /** Proses registrasi akun pelanggan baru. */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => 'pelanggan',
        ]);

        Auth::login($user);

        return redirect()->route('pelanggan.dashboard')
            ->with('success', 'Selamat datang, ' . $user->name . '! Akun berhasil dibuat.');
    }

    /** Logout dan hapus session. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda berhasil keluar.');
    }

    // ─── Helper ───────────────────────────────────────────────────────────────

    /** Redirect user ke dashboard sesuai role. */
    private function redirectByRole()
    {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('pelanggan.dashboard');
    }
}
