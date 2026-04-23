<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuthApiController — autentikasi berbasis Sanctum token untuk aplikasi mobile.
 */
class AuthApiController extends Controller
{
    /**
     * Login menggunakan email atau username, kembalikan bearer token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginField = $request->login;

        $credentials = filter_var($loginField, FILTER_VALIDATE_EMAIL)
            ? ['email' => $loginField, 'password' => $request->password]
            : ['username' => $loginField, 'password' => $request->password];

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email/username atau password salah.',
            ], 401);
        }

        /** @var User $user */
        $user  = Auth::user();
        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'user'  => [
                    'id'       => $user->id,
                    'name'     => $user->name,
                    'username' => $user->username,
                    'email'    => $user->email,
                    'role'     => $user->role,
                ],
                'token' => $token,
            ],
        ]);
    }

    /**
     * Registrasi akun pelanggan baru, kembalikan bearer token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'username' => $request->username,
                'email'    => $request->email,
                'password' => $request->password,
                'role'     => 'pelanggan',
            ]);

            $token = $user->createToken('mobile-token')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'Registrasi berhasil.',
                'data'    => [
                    'user'  => [
                        'id'       => $user->id,
                        'name'     => $user->name,
                        'username' => $user->username,
                        'email'    => $user->email,
                        'role'     => $user->role,
                    ],
                    'token' => $token,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Registrasi gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout — cabut token yang sedang aktif.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Kembalikan data user yang sedang login.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data'   => $request->user(),
        ]);
    }
}
