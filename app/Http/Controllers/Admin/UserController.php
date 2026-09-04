<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\User;

use App\Services\AuditLogService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{


    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('id')->get();

        $users = User::where('role', 'user')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.users.index',
            compact('admins', 'users')
        );
    }



    public function create()
    {

        return view(
            'admin.users.create'
        );

    }



    public function store(
        Request $request
    )
    {


        $validated = $request->validate([

            'name' => [

                'nullable',

                'string',

                'max:255'

            ],


            'username' => [

                'required',

                'string',

                'max:255',

                'unique:users,username'

            ],


            'email' => [

                'nullable',

                'email',

                'max:255',

                'unique:users,email'

            ],

            'no_wa' => [

                'nullable',

                'string',

                'max:20'

            ],


            'password' => [

                'required',

                'min:8'

            ],


            'role' => [

                'required',

                'in:admin,user'

            ],


            'nama_unit' => [

                'required',

                'string'

            ],


            'kode_unit' => [

                'required',

                'string'

            ],

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

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $user->id,
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'no_wa' => [
                'nullable',
                'string',
                'max:20',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
            ],
            'role' => [
                'required',
                'in:admin,user',
            ],
            'nama_unit' => [
                'nullable',
                'string',
            ],
            'kode_unit' => [
                'nullable',
                'string',
            ],
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

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User berhasil diperbarui.' . ($passwordChanged ? ' Password user berhasil diubah.' : '')
            );
    }

    public function destroy(User $user)
    {
        $roleVal = is_object($user->role) ? $user->role->value : $user->role;
        if ($roleVal === 'admin') {
            return back()->with('error', 'Akun Administrator dilindungi dan tidak dapat dihapus demi keamanan sistem.');
        }

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        if ($user->pemesanan()->exists()) {
            return back()->with('error', "User '{$user->name}' tidak dapat dihapus karena memiliki riwayat pemesanan ruangan. Data akun harus dipertahankan untuk kebutuhan audit.");
        }

        $username = $user->username;

        $user->delete();

        AuditLogService::create(
            'Menghapus User',
            'User Management',
            'Menghapus user ' . $username
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }


}