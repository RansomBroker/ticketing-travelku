@extends('master')

@section('title', 'Admin: Kelola Penjualan')

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
          <h5 class="card-header">Kelola Invoice</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped" id="table-supplier">
              <thead>
                <tr>
                  <td>Supplier</td>
                  <td>PNR</td>
                  <td>Harga Jual</td>
                  <td>Agent</td>
                  <td>Dijual Kepada</td>
                  <td>Aksi</td>
                </tr>
              </thead>
              <tbody>
                @foreach($data as $item)
                  <tr>
                    <td>
                      <div class="wrap">
                        @if($item->deposit == 'Lunas')
                        <div class="badge bg-success mb-2">{{ $item->deposit }}</div>
                        @elseif($item->deposit == 'Tersimpan')
                        <div class="badge bg-secondary mb-2">{{ $item->deposit }}</div>
                        @else
                        <div class="badge bg-warning mb-2">{{ $item->deposit }}</div>
                        @endif
                        <h6>{{ $item->supplier_name }}</h6>
                      </div>
                    </td>
                    <td>{{ $item->pnr }}</td>
                    <td>{{ number_format($item->sell_price, 0, ',', '.') }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->sell_to }}</td>
                    <td>
                      <div class="d-flex">
                        <a href="{{ URL::to('admin/manage/acc/'.$item->agentid) }}" class="btn btn-success btn-sm">
                          <i class='bx bx-check'></i>
                        </a>
                        <a href="{{ URL::to('admin/manage/deny/'.$item->agentid) }}" class="btn btn-danger btn-sm mx-2">
                          <i class='bx bx-x'></i>
                        </a>
                        <a href="{{ URL::to('admin/view/'.$item->agentid) }}" class="btn btn-primary btn-sm">
                          <i class='bx bx-info-circle'></i>
                        </a>
                      </div>
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
  $("#table-supplier").DataTable({responsive: true})
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