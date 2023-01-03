<table class="table" data-toolbar="#toolbar">
  <thead>
    <tr>
      <th>Nombre</th>
      <th>Correo Electrónico</th>
      <th>Fecha de Registro</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach($users as $user)
    <tr>
      <td style="display: none;">{{ $user->id }}</td>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ date("d/m/Y", strtotime($user->created_at)) }}</td>
      <td>
        <div class="dropdown">
          <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
          </button>
          <div class="dropdown-menu dropdown-menu-end">
            <a value="{{ $user->id }}" id="editUsers" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editUserModal" OnClick='editUsers({{ $user->id }});'>
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
              <span>Editar</span>
            </a>
            <a class="dropdown-item" href="#">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
              <span>Eliminar</span>
            </a>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<hr class="my-0" />

<div id="paginatorTableUsers" class="d-flex justify-content-between p-2">

Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de un total de {{ $users->total() }} registros

{{ $users->links() }}

</div>
