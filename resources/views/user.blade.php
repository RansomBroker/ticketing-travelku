@extends('master')

@section('title', 'Admin: Data Agent')

@section('custom-css')
@endsection

@section('content')

@section('menu')
  @include('components/menu')
@endsection

@section('navbar')
  @include('components/navbar')
@endsection

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row gx-4 gy-4">
    <div class="col-lg-12">
      <div class="card">
        <div class="d-flex align-items-center justify-content-between">
          <h5 class="card-header">Agent/Marketing</h5>
          <button data-bs-toggle="modal" data-bs-target="#add-data" class="btn btn-primary m-3">Tambah Data</button>
        </div>
        <div class="card-body">
          <div class="row gy-3">
            <div class="col-lg-12">
              <div class="row">
                <div class="table-responsive">
                  <table class="table table-striped" id="table-user">
                    <thead>
                      <tr>
                        <td>Nama Agent</td>
                        <td>Email</td>
                        <td>Aksi</td>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($user as $item)
                      <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                          <button key="{{ $item->id }}" class="btn btn-sm btn-primary open-edit"><i class="bx bx-pencil"></i></button>
                          <a href="{{ URL::to('admin/user/'.$item->id) }}" class="btn btn-sm btn-danger"><i class="bx bx-trash"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add-data" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Agent/Marketing</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="{{ URL::to('admin/user') }}" method="POST">
        <div class="modal-body">
          @csrf
          <div class="row gy-2">
            <div class="col-lg-6 form-group">
              <label class="mb-2" for="name">Nama</label>
              <input class="form-control" name="name" id="name" placeholder="Nama Agent" required>
            </div>
            <div class="col-lg-6 form-group">
              <label class="mb-2" for="email">Email</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
            <!-- <div class="col-lg-12 form-group">
              <label class="mb-2" for="password">Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="col-lg-12 form-group">
              <label class="mb-2" for="password_confirmation">Konfirmasi Password</label>
              <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Password Confirmation" required>
            </div> -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Tutup
          </button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-data" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Agent/Marketing</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="{{ URL::to('admin/user/save') }}" method="POST">
        <input type="hidden" name="id">
        <div class="modal-body">
          @csrf
          <div class="row gy-2">
            <div class="col-lg-6 form-group">
              <label class="mb-2" for="name">Nama</label>
              <input class="form-control" name="name" id="name" placeholder="Nama Agent" required>
            </div>
            <div class="col-lg-6 form-group">
              <label class="mb-2" for="email">Email</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            Tutup
          </button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div
  class="bs-toast toast bg-danger top-0 end-0 toast-placement-ex m-2"
  role="alert"
  aria-live="assertive"
  aria-atomic="true"
  data-delay="0"
>
  <div class="toast-header">
    <i class="bx bx-bell me-2"></i>
    <div class="me-auto fw-semibold">Bootstrap</div>
    <small>11 mins ago</small>
    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body">Fruitcake chocolate bar tootsie roll gummies gummies jelly beans cake.</div>
</div>

@section('custom-js')
<script type="text/javascript">
  $("#table-user").DataTable()

  $(".open-edit").click(async function() {
    const id = $(this).attr('key')
    const response = await fetch('{{ URL::to("admin/user/fetch") }}' + '/' + id)
    const data = await response.json()

    $('#edit-data [name=id]').val(data.id)
    $('#edit-data [name=name]').val(data.name)
    $('#edit-data [name=email]').val(data.email)
    $('#edit-data').modal('show')
  })
</script>

@if(Session::has('success'))
  <script>
    const toastPlacementExample = document.querySelector('.toast-placement-ex')
    toastPlacementExample.classList.remove('bg-danger')
    toastPlacementExample.classList.add('bg-success')
    toastPlacementExample.innerHTML = `
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold">Success</div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">{{ Session::get('success') }}</div>
    `
    toastPlacement = new bootstrap.Toast(toastPlacementExample)
    toastPlacement.show()
  </script>
@endif

@if(Session::has('error'))
  <script>
    const toastPlacementExample = document.querySelector('.toast-placement-ex')
    toastPlacementExample.innerHTML = `
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold">Error</div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body">{{ Session::get('error') }}</div>
    `
    toastPlacement = new bootstrap.Toast(toastPlacementExample)
    toastPlacement.show()
  </script>
@endif
@endsection

@endsection