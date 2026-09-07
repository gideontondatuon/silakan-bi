<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends Controller
{
    /**
     * List users separated by admins and regular users.
     */
    public function index(Request $request): JsonResponse
    {
        $admins = User::with('department')->where('role', 'admin')->orderBy('id')->get();

        $query = User::with('department')->where('role', 'user');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhere('nama_unit', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->latest('id')->paginate($request->get('per_page', 15));
        $departments = Department::orderBy('nama_department')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'admins' => $admins,
                'users' => $users,
                'departments' => $departments,
            ],
        ]);
    }

    /**
     * Store new user.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'no_wa' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,user'],
            'nama_unit' => ['required', 'string'],
            'kode_unit' => ['required', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $rawPassword = $validated['password'];
        $validated['password'] = Hash::make($rawPassword);
        $validated['password_plain'] = $rawPassword;

        $user = User::create($validated);

        AuditLogService::create(
            'Menambahkan User',
            'User Management',
            'Menambahkan user ' . $user->username
        );

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dibuat.',
            'data' => $user->load('department'),
        ], 201);
    }

    /**
     * Show single user details.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $user->load('department'),
        ]);
    }

    /**
     * Update user.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_wa' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin,user'],
            'nama_unit' => ['nullable', 'string'],
            'kode_unit' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $roleLama = is_object($user->role) ? $user->role->value : $user->role;
        if ($roleLama === 'admin') {
            $validated['role'] = 'admin';
        }
        $passwordChanged = false;

        if (!empty($validated['password'])) {
            $rawPassword = $validated['password'];
            $validated['password'] = Hash::make($rawPassword);
            $validated['password_plain'] = $rawPassword;
            $passwordChanged = true;
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $roleBaru = is_object($user->role) ? $user->role->value : $user->role;
        $logDetail = "Memperbarui user {$user->username}. Role: {$roleLama} -> {$roleBaru}";
        if ($passwordChanged) {
            $logDetail .= " (Password direset oleh admin)";
        }

        AuditLogService::create(
            'Memperbarui User',
            'User Management',
            $logDetail
        );

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil diperbarui.' . ($passwordChanged ? ' Password user berhasil diubah.' : ''),
            'data' => $user->load('department'),
        ]);
    }

    /**
     * Delete user.
     */
    public function destroy(User $user): JsonResponse
    {
        $roleVal = is_object($user->role) ? $user->role->value : $user->role;
        if ($roleVal === 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun Administrator dilindungi dan tidak dapat dihapus demi keamanan sistem.',
            ], 422);
        }

        if ($user->id === auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.',
            ], 422);
        }

        if ($user->pemesanan()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => "User '{$user->name}' tidak dapat dihapus karena memiliki riwayat pemesanan ruangan. Data akun harus dipertahankan untuk kebutuhan audit.",
            ], 422);
        }

        $username = $user->username;
        $user->delete();

        AuditLogService::create(
            'Menghapus User',
            'User Management',
            'Menghapus user ' . $username
        );

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
