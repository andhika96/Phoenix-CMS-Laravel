@extends('themes.default.admin.admin_layout')

@section('content')
	<div class="arv6-box p-4">
		<div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-4 border-bottom">
			<div class="h5 mb-0">
				<i class="fad fa-user-secret fa-fw me-1"></i> Awesome Admin
			</div>

			<div class="mt-3 mt-lg-0 d-none">
				<div class="row g-3">
					<div class="col-auto">
						<a href="#!" class="btn btn-primary">TESTING</a>
					</div>

					<div class="col-auto">
						<a href="#!" class="btn btn-primary">TESTING</a>
					</div>
				</div>
			</div>
		</div>

		<div>
			@foreach($forms as $key0 => $value0)
				@if($key0 == 'schema')
					@foreach($value0 as $key1 => $value1)
						<input type="{{ $value1['type'] }}" name="{{ $key1 }}" placeholder="{{ $value1['placeholder'] }}" class="form-control font-size-inherit">
					@endforeach
				@endif 
			@endforeach
		</div>
	</div>
@endsection