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
        <h5 class="card-header">Cari Data</h5>
        <form action="{{ URL::to('admin/report') }}" method="get">
          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 form-group">
                <label class="mb-2">Dari Bulan</label>
                <input class="form-control" name="from" type="month" placeholder="Select Month" value="{{ $_GET['from'] ?? '2023-01' }}" required>
              </div>
              <div class="col-lg-6 form-group">
                <label class="mb-2">Ke Bulan</label>
                <input class="form-control" name="to" type="month" placeholder="Select Month" value="{{ $_GET['to'] ?? '2023-12' }}" required>
              </div>
              <div class="col-lg-12">
                <button type="submit" class="btn btn-primary mt-3">Cari</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="card">
        <div class="d-flex align-items-center justify-content-between">
          <h5 class="card-header">Laporan</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-supplier">
              <thead>
                <tr>
                  <td>No</td>
                  <td>Bulan</td>
                  <td>Jumlah</td>
                  <td>Aksi</td>
                </tr>
              </thead>
              <tbody>
                @foreach($report as $k => $item)
                  <tr>
                    <td>{{ $k+1 }}</td>
                    <td>{{ $month[$k].'/'.$year }}</td>
                    <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>
                      <a class="btn btn-primary btn-sm" href="{{ URL::to('admin/agent?month='.$item->bulan) }}">
                        <i class="bx bx-info-circle"></i>
                      </a>
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

</div>
</div>


@section('custom-js')
<script type="text/javascript">
  $(".table-supplier").DataTable({responsive: true})
  const fromEl = document.querySelector('[name=from]');
  const toEl = document.querySelector('[name=to]');

  fromEl.addEventListener('change', (e) => {
    const value = e.currentTarget.value;
    const val = value.split('-');
    toEl.min = val[0] + '-' + ((parseInt(val[1]) + 1) > 12 ? 12 : (parseInt(val[1]) + 1) > 9 ? parseInt(val[1]) + 1 : '0' + parseInt(parseInt(val[1]) + 1))
    toEl.max = val[0] + '-12'
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