@extends('themes.default.admin.admin_layout')

@section('content')
	<div class="arv6-box p-4">
		<div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-2 border-bottom">
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

		<div class="row g-5 g-lg-4 mt-0 text-center">
			<div class="col-6 col-md-3 col-xl-2">
				<a href="{{ url('awesome_admin/config') }}" class="text-decoration-none">
					<div class="d-block mb-2">
						<i class="fad fa-cogs fa-3x"></i>
					</div>

					<span class="lead">Configurations</span>
				</a>
			</div>

			<div class="col-6 col-md-3 col-xl-2">
				<a href="{{ url('awesome_admin/modules') }}" class="text-decoration-none">
					<div class="d-block mb-2">
						<i class="fad fa-puzzle-piece fa-3x"></i>
					</div>

					<span class="lead">Modules</span>
				</a>
			</div>

			<div class="col-6 col-md-3 col-xl-2">
				<a href="{{ url('awesome_admin/config') }}" class="text-decoration-none">
					<div class="d-block mb-2">
						<i class="fad fa-users-class fa-3x"></i>
					</div>

					<span class="lead">Menu Access</span>
				</a>
			</div>

			<div class="col-6 col-md-3 col-xl-2">
				<a href="{{ url('awesome_admin/user') }}" class="text-decoration-none">
					<div class="d-block mb-2">
						<i class="fad fa-users fa-3x"></i>
					</div>

					<span class="lead">User</span>
				</a>
			</div>

			<div class="col-6 col-md-3 col-xl-2">
				<a href="{{ url('awesome_admin/role') }}" class="text-decoration-none">
					<div class="d-block mb-2">
						<i class="fad fa-project-diagram fa-3x"></i>
					</div>

					<span class="lead">Roles</span>
				</a>
			</div>

			<div class="col-6 col-md-3 col-xl-2">
				<a href="{{ url('awesome_admin/permission') }}" class="text-decoration-none">
					<div class="d-block mb-2">
						<i class="fad fa-chart-network fa-3x"></i>
					</div>

					<span class="lead">Permissions</span>
				</a>
			</div>
		</div>
	</div>
@endsection