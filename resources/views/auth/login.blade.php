@extends('themes.default.layout')

@section('content')
	<style>

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

	</style>

	<div>    
		<div class="row g-0">
			<div class="col-lg-5 d-flex justify-content-center align-items-center">
				<div class="w-100 p-5">
					<div class="mb-5 text-center">
						<h6 class="h3">Login</h6>
						<p class="text-muted mb-0">Sign in to your account to continue.</p>
					</div>

					<div class="d-block">
						<div class="form-floating mb-3">
							<input type="email" class="form-control font-size-inherit" id="floatingInput" placeholder="name@example.com">
							<label for="floatingInput">Email address</label>
						</div>

						<div class="form-floating">
							<input type="password" class="form-control" id="floatingPassword" placeholder="Password">
							<label for="floatingPassword">Password</label>
						</div>				
					</div>
				</div>
			</div>

			<div class="col-lg-7 p-0">
				<div class="ph-bg-image ph-border-bottom-left-leaf" style="background-image: url({{ asset('assets/images/pexels-quang-nguyen-vinh-222549-2649403.jpg') }});"></div>

				<img src="{{ asset('assets/images/pexels-quang-nguyen-vinh-222549-2649403.jpg') }}" class="img-fluid d-none">
			</div>
		</div>
	</div>
@endsection