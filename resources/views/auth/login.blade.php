@extends('themes.default.layout')

@section('title', 'Login')

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

		box-shadow: -1px 2px 5px 0px rgba(0,0,0,0.25);
		-webkit-box-shadow: -1px 2px 5px 0px rgba(0,0,0,0.25);
		-moz-box-shadow: -1px 2px 5px 0px rgba(0,0,0,0.25);
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

	<div id="ph-app-auth">    
		<div class="row g-0">
			<div class="col-lg-5 d-flex justify-content-center align-items-center vh-100">
				<div class="w-100 ph-form-auth">
					<div class="mb-5 text-center">
						<h6 class="h3">Login</h6>
						<p class="text-muted mb-0">Sign in to your account to continue.</p>

						<div class="ph-notice">
							<div aria-live="polite" aria-atomic="true" class="position-relative">
								<div class="toast-container position-fixed top-0 end-0 p-3">
									<div class="toast ph-notice-toast text-bg-danger" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
										<div class="toast-header text-bg-danger border-0" data-bs-theme="dark">
											<strong class="me-auto">Notice</strong>
											<small>just now</small>
											<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" style="margin-right: calc(-.1 * var(--bs-toast-padding-x));"></button>
										</div>
									
										<div class="toast-body text-start">
											<div v-if="isArrayMessageAfterSubmit == 1">
												<ul class="ps-3 m-0">
													<li v-for="(item, index) in responseMessageAfterSubmit">
														@{{ item[0] }}
													</li>
												</ul>
											</div>

											<div v-else>
												@{{ responseMessageAfterSubmit }}
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="d-block" id="ph-form-login-submit">
						<form action="{{ route('auth.login.authenticate') }}" method="post" @submit="login" ref="formHTML">
							<div class="input-group ph-input-group mb-3">
								<span class="input-group-text"><i class="fa fa-user"></i></span>
								
								<div class="form-floating">
									<input type="text" name="email" class="form-control form-control-sm font-size-inherit" id="floatingInput" placeholder="name@example.com">
									<label for="floatingInput">Email address</label>
								</div>
							</div>

							<div class="input-group ph-input-group mb-3">
								<span class="input-group-text"><i class="fad fa-key"></i></span>

								<div class="form-floating">
									<input type="password" name="password" class="form-control form-control-sm font-size-inherit" id="floatingPassword" placeholder="Password">
									<label for="floatingPassword">Password</label>
								</div>	
							</div>	

							<div class="my-3 text-end">
								<a href="{{ url('auth/forgotpassword') }}">Lost Password?</a>
							</div>

							<div class="d-block mb-3">
								<input type="submit" class="btn btn-primary btn-submit-login rounded-pill p-2 w-100" value="Login">
							</div>

							<div class="text-center">
								<span class="text-body-tertiary">Not registered?</span> <a href="{{ url('auth/signup') }}">Create account</a>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-lg-7 p-0 ph-border-bottom-left-leaf d-none d-lg-flex justify-content-center align-items-center" style="background-color: #ffeaea;">
				<div class="ph-bg-image ph-border-bottom-left-leaf d-none" style="background-image: url({{ asset('assets/images/trees-on-forest-at-daytime-1083515.jpg') }});"></div>
				
				<img src="{{ asset('assets/svgs/undraw_welcome_re_h3d9.svg') }}" class="100-h" style="width: 300px;">
				<img src="{{ asset('assets/images/pexels-visually-us-1643402.jpg') }}" class="img-fluid d-none">
			</div>
		</div>
	</div>
@endsection