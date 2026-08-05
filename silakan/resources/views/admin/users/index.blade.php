<x-app-layout>

<div class="dashboard-header">

    <h1>
        Data User
    </h1>

    <p>
        Manajemen akun pengguna SILAKAN.
    </p>

</div>


<div class="dashboard-section">


<a href="{{ route('admin.users.create') }}"
   class="btn-primary">

    Tambah User

</a>


<table class="data-table">

<thead>

<tr>

<th>
No
</th>

<th>
Username
</th>

<th>
Nama
</th>

<th>
Unit
</th>

<th>
Role
</th>

<th>
Aksi
</th>

</tr>

</thead>


<tbody>


@forelse($users as $user)


<tr>

<td>
{{ $loop->iteration }}
</td>


<td>
{{ $user->username }}
</td>


<td>
{{ $user->name ?? '-' }}
</td>


<td>
{{ $user->nama_unit }}
</td>


<td>
    {{ strtoupper($user->role->value) }}
</td>


<td>


<a href="{{ route(
    'admin.users.edit',
    $user
) }}">

Edit

</a>



<form method="POST"
      action="{{ route(
          'admin.users.destroy',
          $user
      ) }}"
      style="display:inline">

    @csrf

    @method('DELETE')


    <button type="submit">

        Hapus

    </button>

</form>


</td>

</tr>


@empty


<tr>

<td colspan="6">

Belum ada data user.

</td>

</tr>


@endforelse


</tbody>


</table>


{{ $users->links() }}


</div>


</x-app-layout>