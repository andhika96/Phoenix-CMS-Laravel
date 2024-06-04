@extends('themes.default.admin.admin_layout')

@section('content')
	{{ Breadcrumbs::render('config') }}

	<div class="arv6-box p-4">
		<div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-4 border-bottom">
			<div class="h5 mb-0">
				<i class="fad fa-cogs fa-fw me-1"></i> Site Config
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
			<div class="row g-5">
				<div class="col-lg-6">
					<div class="row g-3">
						<div class="col-12">
							<label class="form-label">Site Name</label>
							<input type="text" name="site_name" class="form-control font-size-inherit">
						</div>

						<div class="col-12">
							<label class="form-label">Site Slogan</label>
							<input type="text" name="site_name" class="form-control font-size-inherit">
						</div>

						<div class="col-12">
							<label class="form-label">Site Description</label>
							<input type="text" name="site_name" class="form-control font-size-inherit">
						</div>

						<div class="col-12">
							<label class="form-label">Site Registration Settings</label>

							<select class="form-select font-size-inherit" aria-label="Default select example">
								<option selected>Select</option>
								<option value="0">Open - Accepting new members</option>
								<option value="1">Close - Not accepting new members</option>
							</select>
						</div>

						<div class="col-12">
							<label class="form-label">Site Maintenance Settings</label>

							<select class="form-select font-size-inherit" aria-label="Default select example">
								<option selected>Select</option>
								<option value="0">Active</option>
								<option value="1">Inactive</option>
							</select>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="row g-3">
						<div class="col-12">
							<label class="form-label">Site Thumbnail</label>
								<img src="{{ asset('assets/images/aruna_card_1200.jpg') }}" class="img-fluid rounded mb-3">

							  <input class="form-control font-size-inherit" type="file" id="formFile">
						</div>
					</div>
				</div>

				<div class="col-12 mt-3 text-end">
					<input type="submit" class="btn btn-primary btn-submit-data font-size-inherit" value="Submit">
				</div>
			</div>

		</div>
	</div>
@endsection