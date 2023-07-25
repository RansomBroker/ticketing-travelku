@extends('master')

@section('title', 'Admin: Login')

@section('custom-css')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
        <div class="card-body">
          <h4 class="mb-2">Login! 👋</h4>
          <p class="mb-4">Please sign-in to manage data</p>

          <form id="formAuthentication" class="mb-3" action="{{ URL::to('auth/login') }}" method="POST">
          	@csrf
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="text"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your email"
                autofocus
                required
              />
            </div>
            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
              </div>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password"
                  class="form-control"
                  name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password"
                  required
                />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
            </div>
          </form>
        </div>
      </div>
      <!-- /Register -->
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
@endsection

@section('custom-js')
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