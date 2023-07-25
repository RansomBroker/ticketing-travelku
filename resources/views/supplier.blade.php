@extends('master')

@section('title', 'Admin: Stok Tiket')

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
        <div class="card-body">
          <div class="card-title d-flex align-items-start justify-content-between">
            <div class="avatar flex-shrink-0">
              <img src="../assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded">
            </div>
          </div>
          <span class="fw-semibold d-block mb-1">Stok Tenggat</span>
          <h3 class="card-title mb-2">{{ count($due) }}</h3>
          <small class="text-muted fw-semibold">Stok Melebihi Tenggat Waktu</small>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="d-flex align-items-center justify-content-between">
          <h5 class="card-header">Stokist</h5>
          <button data-bs-toggle="modal" data-bs-target="#add-data" class="btn btn-primary m-3">Tambah Data</button>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-supplier">
              <thead>
                <tr>
                  <td>Supplier</td>
                  <td>Kontak</td>
                  <td>PNR</td>
                  <td>Kursi</td>
                  <td>Harga Beli</td>
                  <td>Harga Jual</td>
                  <td>Aksi</td>
                </tr>
              </thead>
              <tbody>
                @foreach($supplier as $item)
                  <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->pnr }}</td>
                    <td>{{ $item->seat }} Kursi</td>
                    <td>{{ number_format($item->buy_price, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->sells, 0, ',', '.') }}</td>
                    <td>
                      <button key="{{ $item->supplier_id }}" class="btn btn-primary btn-sm open-edit" id="open-edit"><i class="bx bx-pencil"></i></button>
                      <a href="{{ URL::to('admin/supplier/'.$item->supplier_id ) }}" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="d-flex align-items-center justify-content-between">
          <h5 class="card-header">Stok Melebihi Tenggat Waktu</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-supplier">
              <thead>
                <tr>
                  <td>Supplier</td>
                  <td>Kontak</td>
                  <td>PNR</td>
                  <td>Kursi</td>
                  <td>Harga Beli</td>
                  <td>Harga Jual</td>
                </tr>
              </thead>
              <tbody>
                @foreach($due as $item)
                  <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->pnr }}</td>
                    <td>{{ $item->seat }} Kursi</td>
                    <td>{{ number_format($item->buy_price, 0, ',', '.') }}</td>
                    <td>{{ number_format($item->sells, 0, ',', '.') }}</td>
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

<div class="modal fade" id="add-data" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Stok</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="{{ URL::to('admin/supplier') }}" method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col mb-2">
            @csrf
            <label class="form-label">Supplier</label>
            <select class="form-select select2" name="produsen_id" required>
              @foreach($produsen as $item)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="row g-2">
          <div class="col mb-2">
            <label class="form-label">PNR</label>
            <input
              type="text"
              name="pnr"
              class="form-control"
              placeholder="PNR"
              required
            />
          </div>
          <div class="col mb-2">
            <label class="form-label">Total Kursi</label>
            <input
              type="number"
              name="seat"
              class="form-control"
              placeholder="Total Kursi"
              required
            />
          </div>
        </div>
        <div class="row g-2">
          <div class="col mb-2">
            <label class="form-label">Tenggat</label>
            <input
              type="datetime-local"
              class="form-control"
              name="time_limit"
              placeholder="Tenggat Invoice"
              required
            />
          </div>
        </div>
        <div class="row g-2">
          <div class="col">
            <textarea placeholder="Schedule" class="form-control my-2" rows="5" name="schedule" required></textarea>
          </div>
        </div>
        <div class="row">
          <div class="col mb-2">
            <label class="form-label">Harga Beli</label>
            <input
              type="text"
              class="form-control"
              name="buy_price"
              placeholder="Harga Beli"
              required
            />
          </div>
          <div class="col mb-2">
            <label class="form-label">Harga Jual</label>
            <input
              type="text"
              class="form-control"
              name="sell_price"
              placeholder="Harga Jual"
              required
            />
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

<div class="modal fade" id="edit-data" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Stok</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="{{ URL::to('admin/supplier/edit') }}" method="POST">
      <input type="hidden" name="id">
      <div class="modal-body">
        <div class="row">
          <div class="col mb-2">
            @csrf
            <label class="form-label">Supplier</label>
            <select class="form-select select2" name="produsen_id" required>
              @foreach($produsen as $item)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="row g-2">
          <div class="col mb-2">
            <label class="form-label">PNR</label>
            <input
              type="text"
              name="pnr"
              class="form-control"
              placeholder="PNR"
              required
            />
          </div>
          <div class="col mb-2">
            <label class="form-label">Total Kursi</label>
            <input
              type="number"
              name="seat"
              class="form-control"
              placeholder="Total Kursi"
              required
            />
          </div>
        </div>
        <div class="row g-2">
          <div class="col mb-2">
            <label class="form-label">Tenggat</label>
            <input
              type="datetime-local"
              class="form-control"
              name="time_limit"
              placeholder="Tenggat Invoice"
              required
            />
          </div>
        </div>
        <div class="row g-2">
          <div class="col">
            <textarea placeholder="Schedule" class="form-control my-2" rows="5" name="schedule" required></textarea>
          </div>
        </div>
        <div class="row">
          <div class="col mb-2">
            <label class="form-label">Harga Beli</label>
            <input
              type="text"
              class="form-control"
              name="buy_price"
              placeholder="Harga Beli"
              required
            />
          </div>
          <div class="col mb-2">
            <label class="form-label">Harga Jual</label>
            <input
              type="text"
              class="form-control"
              name="sell_price"
              placeholder="Harga Jual"
              required
            />
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

</div>
</div>


@section('custom-js')
<script type="text/javascript">
  $(".table-supplier").DataTable()

  $("[name=sell_price]").mask("#.##0", {reverse: true});
  $("[name=buy_price]").mask("#.##0", {reverse: true});

  $(".open-edit").click(async function(e) {
    e.preventDefault();
    const id = $(this).attr('key')
    const url = "{{ URL::to('admin/supplier/fetch') }}" + '/' + id
    const response = await fetch(url);
    const data = await response.json()

    $('#edit-data [name=id]').val(data.id)
    $('#edit-data [name=sell_price]').val(formatRupiah(data.sell_price))
    $('#edit-data [name=buy_price]').val(formatRupiah(data.buy_price))
    $('#edit-data [name=time_limit]').val(data.time_limit)
    $('#edit-data [name=schedule]').val(data.schedule)
    $('#edit-data [name=seat]').val(data.seat)
    $('#edit-data [name=pnr]').val(data.pnr)

    $('#edit-data').modal('show');
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