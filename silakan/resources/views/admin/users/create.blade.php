<x-app-layout>

<div class="dashboard-header">

    <h1>
        Tambah User
    </h1>

</div>


<div class="dashboard-section">


<form method="POST"
      action="{{ route('admin.users.store') }}">

@csrf


<label>
Username
</label>

<input type="text"
       name="username"
       required>



<label>
Nama
</label>

<input type="text"
       name="name">



<label>
Email
</label>

<input type="email"
       name="email">



<label>
Password
</label>

<input type="password"
       name="password"
       required>



<label>
Nama Unit
</label>

<input type="text"
       name="nama_unit"
       required>



<label>
Kode Unit
</label>

<input type="text"
       name="kode_unit"
       required>



<label>
Role
</label>

<select name="role">


<option value="user">
USER
</option>


<option value="admin">
ADMIN
</option>


</select>



<button class="btn-primary">

Simpan

</button>


</form>


</div>


</x-app-layout>