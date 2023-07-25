<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo text-center">
    <a href="{{ URL::to('/') }}" class="app-brand-link">
      <img style="width: 50px" src="{{ asset('assets/img/logo.jpg') }}">
    </a> 

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    @if(Auth::user()->role == 'admin')
    <li class="menu-item {{ strpos(Request::url(), 'produsen') ? 'active' : '' }}">
      <a href="{{ URL::to('admin/produsen') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div data-i18n="Analytics">Supplier</div>
      </a>
    </li>
    <li class="menu-item {{ strpos(Request::url(), 'user') ? 'active' : '' }}">
      <a href="{{ URL::to('admin/user') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-user-circle"></i>
        <div data-i18n="Analytics">Agent/Marketing</div>
      </a>
    </li>
    <li class="menu-item {{ strpos(Request::url(), 'supplier') ? 'active' : '' }}">
      <a href="{{ URL::to('admin/supplier') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-wallet"></i>
        <div data-i18n="Analytics">Stokist</div>
      </a>
    </li>
    <li class="menu-item {{ strpos(Request::url(), 'manage') ? 'active' : '' }}">
      <a href="{{ URL::to('admin/manage') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-wallet"></i>
        <div data-i18n="Analytics">Kelola Invoice</div>
      </a>
    </li>
    @endif
    <li class="menu-item {{ strpos(Request::url(), 'agent') ? 'active' : '' }}">
      <a href="{{ URL::to('admin/agent') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-wallet"></i>
        <div data-i18n="Analytics">Penjualan</div>
      </a>
    </li>
    <li class="menu-item {{ strpos(Request::url(), 'report') ? 'active' : '' }}">
      <a href="{{ URL::to('admin/report') }}" class="menu-link">
        <i class='menu-icon tf-icons bx bxs-bar-chart-alt-2'></i>
        <div data-i18n="Analytics">Laporan</div>
      </a>
    </li>
    <li class="menu-item">
      <a href="{{ URL::to('logout') }}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-log-out"></i>
        <div data-i18n="Analytics">Keluar</div>
      </a>
    </li>
  </ul>
</aside>
<!-- / Menu -->