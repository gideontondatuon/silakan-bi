<x-app-layout>

<div class="dashboard-header">

    <h1>
        Edit User
    </h1>

</div>


<div class="dashboard-section">


<form method="POST"
      action="{{ route(
          'admin.users.update',
          $user
      ) }}">

@csrf

@method('PUT')


<label>
Username
</label>

<input type="text"
       name="username"
       value="{{ $user->username }}"
       required>



<label>
Nama
</label>

<input type="text"
       name="name"
       value="{{ $user->name }}">



<label>
Email
</label>

<input type="email"
       name="email"
       value="{{ $user->email }}">



<label>
Nama Unit
</label>

<input type="text"
       name="nama_unit"
       value="{{ $user->nama_unit }}"
       required>



<label>
Kode Unit
</label>

<input type="text"
       name="kode_unit"
       value="{{ $user->kode_unit }}"
       required>



<label>
Role
</label>

<select name="role">


<option value="user"
@if($user->role === 'user')
selected
@endif>

USER

</option>


<option value="admin"
@if($user->role === 'admin')
selected
@endif>

ADMIN

</option>


</select>



<button class="btn-primary">

Update

</button>


</form>


</div>


</x-app-layout>