@extends('themes.default.layout')

@section('content')
	<style>

	.ph-form-auth
	{
		padding: 4.5rem;
	}

	@media (max-width: 575.98px)
	{
		.ph-form-auth
		{
			padding: 2.5rem;
		}
	}

	.ph-bg-image
	{
		width: 100%;
		height: 100vh;
		background-repeat: no-repeat;
		background-position: center center;
		background-size: cover;
	}

	.ph-border-top-left-leaf
	{
		border-top-left-radius: 100px;
		border-top-right-radius: 0;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}

	.ph-border-top-right-leaf
	{
		border-top-left-radius: 0;
		border-top-right-radius: 100px;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 0;
	}

	.ph-border-bottom-left-leaf
	{
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-bottom-left-radius: 100px;
		border-bottom-right-radius: 0;
	}

	.ph-border-bottom-right-leaf
	{
		border-top-left-radius: 0;
		border-top-right-radius: 0;
		border-bottom-left-radius: 0;
		border-bottom-right-radius: 100px;
	}

	.ph-input-group .input-group-text
	{
		border-right: 0;
		background-color: #fff;
		padding-left: 1rem;
		padding-right: 1rem;
		border-top-left-radius: var(--bs-border-radius-pill) !important;
		border-bottom-left-radius: var(--bs-border-radius-pill) !important;
	}

	.ph-input-group .form-control
	{
		border-left: 0;
		padding-left: 0;
		border-top-right-radius: var(--bs-border-radius-pill) !important;
		border-bottom-right-radius: var(--bs-border-radius-pill) !important;
	}

	.ph-input-group .form-floating > label
	{
		padding-left: 0;	
	}

	</style>

	<div>    
		<div class="row g-0">
			<div class="col-lg-5 d-flex justify-content-center align-items-center vh-100">
				<div class="w-100 ph-form-auth">
					<div class="mb-5 text-center">
						<h6 class="h3">Login</h6>
						<p class="text-muted mb-0">Sign in to your account to continue.</p>
					</div>

					<div class="d-block">
						<div class="input-group ph-input-group mb-3">
							<span class="input-group-text"><i class="fad fa-user"></i></span>
							
							<div class="form-floating">
								<input type="email" class="form-control form-control-sm font-size-inherit" id="floatingInput" placeholder="name@example.com">
								<label for="floatingInput">Email address</label>
							</div>
						</div>

						<div class="input-group ph-input-group mb-3">
							<span class="input-group-text"><i class="fad fa-key"></i></span>

							<div class="form-floating">
								<input type="password" class="form-control form-control-sm font-size-inherit" id="floatingPassword" placeholder="Password">
								<label for="floatingPassword">Password</label>
							</div>	
						</div>	

						<div class="my-3 text-end">
							<a href="{{ url('auth/forgotpassword') }}">Lost Password?</a>
						</div>

						<div class="d-block mb-3">
							<input type="submit" class="btn btn-primary rounded-pill p-2 w-100" value="Login">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-7 p-0 d-none d-lg-flex">
				<div class="ph-bg-image ph-border-bottom-left-leaf" style="background-image: url({{ asset('assets/images/pexels-quang-nguyen-vinh-222549-2649403.jpg') }});"></div>

				<img src="{{ asset('assets/images/pexels-quang-nguyen-vinh-222549-2649403.jpg') }}" class="img-fluid d-none">
			</div>
		</div>
	</div>
@endsection