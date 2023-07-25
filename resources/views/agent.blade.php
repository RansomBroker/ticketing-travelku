@extends('master')

@section('title', 'Admin: Stok Terjual')

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
          <h5 class="card-header">Penjualan</h5>
          @if(!isset($_GET['month']))
          <button data-bs-toggle="modal" data-bs-target="#add-data" class="btn btn-primary m-3">Tambah Data</button>
          @endif
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped" id="table-supplier">
              <thead>
                <tr>
                  <td>Supplier</td>
                  <td>PNR</td>
                  <td>Kursi</td>
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
                    <td>{{ $item->seat }} Kursi</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->sell_to }}</td>
                    <td>
                      <div class="d-flex">
                        <a href="{{ URL::to('admin/agent/'.$item->agentid ) }}" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i></a>
                        <button key="{{ $item->agentid }}" id="open-deposit" class="btn btn-success btn-sm mx-2 open-deposit"><i class="bx bx-money"></i></button>
                        <button key="{{ $item->agentid }}" id="open-edit" class="btn btn-primary btn-sm me-2 open-edit"><i class="bx bx-pencil"></i></button>
                        @if($item->acceptance == 'accept')
                        <a href="whatsapp://send?text={{ URL::to('print/'.$item->agentid ) }}" class="btn btn-warning btn-sm"><i class="bx bx-printer"></i></a>
                        @endif
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

<div class="modal fade" id="add-data" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="{{ URL::to('admin/agent') }}" method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col mb-3">
            @csrf
            <label class="form-label">Agent/Marketing</label>
            <select class="form-select select2" name="agent_id" required>
              @foreach($agent as $item)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col mb-3">
            <label class="form-label">PNR</label>
            <select class="form-select select2" name="supplier_id" required>
              @foreach($supplier_add as $item)
              <option value="{{ $item->supplierid }}">{{ $item->pnr }}</option>
              @endforeach
            </select>
          </div>
        </div>
        @if(count($supplier_add) > 0)
        <div class="row">
          <div class="col-lg-12">
            <div class="">
              <table class="table table-bordered">
                <tbody id="view-data">
                  <tr>
                    <td>Seat</td>
                    <td>{{ $supplier_add[0]->seat }}</td>
                  </tr>
                  <tr>
                    <td>Schedule</td>
                    <td>{!! $supplier_add[0]->schedule !!}</td>
                  </tr>
                  <tr>
                    <td>Harga Beli</td>
                    <td>{{ number_format($supplier_add[0]->sells, 0, ',', '.') }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @endif
        <div class="row">
          <div class="col mb-3">
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
        <div class="row">
          <div class="col mb-3">
            <label class="form-label">Dijual Kepada</label>
            <input
              type="text"
              class="form-control"
              name="sell_to"
              placeholder="Dijual Kepada"
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
        <h5 class="modal-title">Edit Data</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form action="{{ URL::to('admin/agent/edit') }}" method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="">
              <table class="table table-bordered">
                <tbody id="view-data">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col mb-3">
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
        <div class="row">
          <div class="col mb-3">
            <label class="form-label">Dijual Kepada</label>
            <input
              type="text"
              class="form-control"
              name="sell_to"
              placeholder="Dijual Kepada"
              required
            />
          </div>
        </div>
      </div>
      <input
              type="hidden"
              class="form-control"
              name="id"
            />
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

<div class="modal fade" id="modal-deposit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Deposit</h5>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"
        ></button>
      </div>
      <form id="adddeposit" action="{{ URL::to('admin/deposit') }}" method="POST">
      <div class="modal-body">
        <div class="row">
          <div class="col">
            <table class="table table-striped">
              <thead>
                <tr>
                  <td>Tanggal</td>
                  <td>Deposit</td>
                  <td>Aksi</td>
                </tr>
              </thead>
              <tbody id="depositBody">
                
              </tbody>
            </table>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-lg-6">
            @csrf
            <input type="hidden" name="agent_id">
            <input class="form-control" placeholder="Date" name="date" type="date" required>
          </div>
          <div class="col-lg-6">
            <input class="form-control" placeholder="500000" name="deposit" type="text" required>
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
  $("#table-supplier").DataTable()

  $("[name=sell_price]").mask("#.##0", {reverse: true});
  $("[name=deposit]").mask("#.##0", {reverse: true});

  $("#add-data [name=supplier_id]").change(async function() {
    const url = '{{ URL::to("admin/supplier/fetch") }}' + '/' + $(this).val()
    const data = await fetch(url);
    const response = await data.json();
    $('#add-data #view-data').html(`
      <tr>
        <td>Seat</td>
        <td>${response.seat}</td>
      </tr>
      <tr>
        <td>Schedule</td>
        <td>${response.schedule}</td>
      </tr>
      <tr>
        <td>Harga Beli</td>
        <td>${formatRupiah(response.sell_price)}</td>
      </tr>
    `)
  })

  $(".open-deposit").click(async function(e) {
    e.preventDefault();
    const agentid = $(this).attr('key')
    const url = "{{ URL::to('admin/deposit') }}" + '/' + agentid
    const response = await fetch(url);
    const data = await response.json()

    $('#modal-deposit').modal('show');

    $("[name=agent_id]").val(agentid)

    let depositHtml = ``
    data.forEach((item) => {
      depositHtml += `
        <tr>
          <td>${item.date}</td>
          <td>${formatRupiah(item.deposit)}</td>
          <td>
            <a class="btn btn-sm btn-danger" href="{{ URL::to('admin/deposit/delete') }}/${item.id}">
              <i class="bx bx-trash"></i>
            </a>
          </td>
        </tr>
      `
    })

    $("#depositBody").html(depositHtml)
  })

  $(".open-edit").click(async function(e) {
    e.preventDefault();
    const agentid = $(this).attr('key')
    const url = "{{ URL::to('admin/agent/fetch') }}" + '/' + agentid
    const response = await fetch(url);
    const data = await response.json()

    const urls = '{{ URL::to("admin/supplier/fetch") }}' + '/' + data.supplier_id
    const datas = await fetch(urls);
    const responses = await datas.json();
    console.log(responses);

    $('#edit-data #view-data').html(`
      <tr>
        <td>PNR</td>
        <td>${responses.pnr}</td>
      </tr>
      <tr>
        <td>Seat</td>
        <td>${responses.seat}</td>
      </tr>
      <tr>
        <td>Schedule</td>
        <td>${responses.schedule}</td>
      </tr>
      <tr>
        <td>Harga Beli</td>
        <td>${formatRupiah(responses.sell_price)}</td>
      </tr>
    `)

    $('#edit-data [name=id]').val(data.id)
    $('#edit-data [name=sell_price]').val(formatRupiah(data.sell_price))
    $('#edit-data [name=sell_to]').val(data.sell_to)
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