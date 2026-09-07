<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthApiController extends Controller
{
    /**
     * Authenticate user and issue Sanctum token + session.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $user = Auth::user();
        $user->load('department');

        $roleValue = is_object($user->role) ? $user->role->value : (string) $user->role;

        // Generate Sanctum plain text token for mobile/external clients
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'nama_unit' => $user->nama_unit,
                    'no_wa' => $user->no_wa,
                    'role' => $roleValue,
                    'department' => $user->department ? [
                        'id' => $user->department->id,
                        'nama_department' => $user->department->nama_unit ?? '',
                        'kode_department' => $user->department->kode_unit ?? '',
                        'nama_unit' => $user->department->nama_unit ?? '',
                        'kode_unit' => $user->department->kode_unit ?? '',
                    ] : null,
                ],
                'token' => $token,
                'role' => $roleValue,
                'redirect_url' => $roleValue === 'admin' ? '/admin/dashboard' : '/dashboard',
            ],
        ]);
    }

    /**
     * Get currently authenticated user profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        $user->load('department');
        $roleValue = is_object($user->role) ? $user->role->value : (string) $user->role;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'nama_unit' => $user->nama_unit,
                'no_wa' => $user->no_wa,
                'role' => $roleValue,
                'department' => $user->department ? [
                    'id' => $user->department->id,
                    'nama_department' => $user->department->nama_unit ?? '',
                    'kode_department' => $user->department->kode_unit ?? '',
                    'nama_unit' => $user->department->nama_unit ?? '',
                    'kode_unit' => $user->department->kode_unit ?? '',
                ] : null,
            ],
        ]);
    }

    /**
     * Update user profile information.
     */
    public function updateProfile(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $user->load('department');

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user,
        ]);
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kata sandi berhasil diperbarui.',
        ]);
    }

    /**
     * Logout and revoke tokens.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            // Revoke current accessToken if using Bearer token
            if (method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil keluar dari sistem.',
        ]);
    }
}
