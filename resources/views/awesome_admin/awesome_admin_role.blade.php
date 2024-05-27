@extends('themes.default.admin.admin_layout')

@section('content')
	<div class="arv6-box p-4" id="ph-list-data-simple">
		<div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-2 border-bottom">
			<div class="h5 mb-0">
				<i class="fad fa-project-diagram fa-fw me-1"></i> Manage Role
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

		<div class="row gx-5 gy-3 gy-lg-3 gx-lg-4 mt-0">
			<div class="col-12 col-lg-6 ar-fetch-listdata-simple" data-url="{{ url('awesome_admin/role/listdata') }}">
				<div class="h6 mb-3"><i class="fad fa-list-ul fa-fw me-1"></i> List of Data</div>

				<ul class="list-group list-group-flush">
					<li class="list-group-item" v-for="(item, index) in responseData">
						@{{ item.name }}
					</li>
				</ul>
			</div>

			<div class="col-12 col-lg-6">
				<div class="h6 mb-3"><i class="fad fa-plus fa-fw me-1"></i> Add New Data</div>

				<div id="ph-submit-data">
					<div class="ph-notice">
						<div aria-live="polite" aria-atomic="true" class="position-relative">
							<div class="toast-container position-fixed top-0 end-0 p-3">
								<div class="toast ph-notice-toast" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
									<div class="toast-header border-0" :class="[responseStatusAfterSubmit ? '' : dangerClass, successClass]" data-bs-theme="dark">
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

					<form action="{{ url('awesome_admin/role') }}" method="post" @submit="submitData" ref="formHTML">
						<div class="form-group mb-3">
							<label class="form-label">Role Name</label>
							<input type="text" name="role_name" class="form-control">
						</div>

						<div class="form-group mb-3">
							<label class="form-label">Permission</label>
							<input type="text" name="permission" class="form-control" disabled>
						</div>

						<div class="form-group text-end">
							<input type="submit" class="btn btn-primary btn-submit-data font-size-inherit" value="Submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection