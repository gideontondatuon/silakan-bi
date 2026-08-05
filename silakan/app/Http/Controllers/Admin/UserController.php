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

        $users = User::latest()
            ->paginate(10);


        return view(
            'admin.users.index',
            compact('users')
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



        $validated['password'] =

            Hash::make(
                $validated['password']
            );



        $user = User::create(
            $validated
        );



        AuditLogService::create(

            'Menambahkan User',

            'User Management',

            'Menambahkan user '
            . $user->username

        );



        return redirect()

            ->route('admin.users.index')

            ->with(
                'success',
                'User berhasil dibuat.'
            );

    }



    public function edit(
        User $user
    )
    {

        return view(
            'admin.users.edit',
            compact('user')
        );

    }



    public function update(
        Request $request,
        User $user
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

                'unique:users,username,' . $user->id

            ],


            'email' => [

                'nullable',

                'email',

                'unique:users,email,' . $user->id

            ],


            'role' => [

                'required',

                'in:admin,user'

            ],


            'nama_unit' => [

                'required'

            ],


            'kode_unit' => [

                'required'

            ],

        ]);



        $roleLama =
            $user->role->value;



        $user->update(
            $validated
        );



        AuditLogService::create(

            'Memperbarui User',

            'User Management',

            'Memperbarui user '
            . $user->username
            . '. Role: '
            . $roleLama
            . ' menjadi '
            . $user->role->value

        );



        return redirect()

            ->route('admin.users.index')

            ->with(
                'success',
                'User berhasil diperbarui.'
            );

    }



    public function destroy(
        User $user
    )
    {


        $username =
            $user->username;



        $user->delete();



        AuditLogService::create(

            'Menghapus User',

            'User Management',

            'Menghapus user '
            . $username

        );



        return redirect()

            ->route('admin.users.index')

            ->with(
                'success',
                'User berhasil dihapus.'
            );

    }


}