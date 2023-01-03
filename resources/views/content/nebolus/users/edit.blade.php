
<form id="formEditUsers" enctype="multipart/form-data" method="POST">
  {{csrf_field()}}
  @method('PUT')
  <input type="hidden" id="id" name="id" value="{{ $user->id }}">
  <div class="row">

    <div class="col-md-6 col-12">
      <label>Nombre: </label>
      <div class="mb-1">
        <input id="name" name="name" type="text" placeholder="Nombre..." class="form-control" value="{{ $user->name }}" />
      </div>
    </div>

    <div class="col-md-6 col-12">

            <!-- header section -->
            <div class="d-flex">
              <a href="#" class="me-25">
                <img
                  src="http://localhost:8000/images/portrait/small/avatar-s-11.jpg"
                  id="account-upload-img"
                  class="uploadedAvatar rounded me-50"
                  alt="profile image"
                  height="100"
                  width="100"
                />
              </a>
              <!-- upload and reset button -->
              <div class="d-flex align-items-end mt-75 ms-1">
                <div>
                  <label for="avatar" class="btn btn-sm btn-primary mb-75 me-75">Cargar</label>
                  <input id="avatar" name="avatar" type="file" hidden accept="image/*" />
                  <button type="button" id="account-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                  <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                </div>
              </div>
              <!--/ upload and reset button -->
            </div>
    </div>

    <div class="col-md-6 col-12">
      <label>Email: </label>
      <div class="mb-1">
        <input id="email" name="email" type="text" placeholder="Correo electrónico..." class="form-control" value="{{ $user->email }}" />
      </div>
    </div>

  </div>

</form>