@extends('themes.default.admin.admin_layout')

@section('content')
    <div class="arv6-box p-4">
        <div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-3 border-bottom">
            <div class="h5">
                Awesome Admin
            </div>

            <div class="mt-3 mt-lg-0">
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

        <div class="row g-4">
            <div class="col-6 col-md-3 col-xl-2 mb-5">
                <a href="{{ url('awesome_admin/config') }}" class="text-decoration-none">
                    <div class="d-block mb-2">
                        <i class="fad fa-cogs fa-4x"></i>
                    </div>

                    <span class="lead">Configurations</span>
                </a>
            </div>
        </div>
    </div>
@endsection