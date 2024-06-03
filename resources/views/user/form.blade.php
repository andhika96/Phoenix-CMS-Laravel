@extends('themes.default.admin.admin_layout')
@section("title") Edit Account @endsection   
@section('content')
    <div class="arv6-box p-4" id="ar-app-form">
        <form action="#" method="post" reset="true" @submit="submitData" ref="formHTML">
            <div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-3 border-bottom">
                <div class="h5">
                    Edit {{ $data?->username ?? 'User' }} Data
                </div>

                <div class="mt-3 mt-lg-0">
                    <div class="row g-3">
                        <div class="col-auto">
                            <a href="#!" class="btn btn-default">Cancel</a>
                        </div>

                        <div class="col-auto">    
                            <a href="#!" class="btn btn-success"><i class="fad fa-save me-2"></i> Save</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                
            </div>
        </form>

    </div>
@endsection
<div class="col-12 mb-4">
                    <label class="form-label">Fullname</label>
                    <div class="input-group">
                        <input id="fullname" name="fullname" class="form-control" type="text" value="{{ $data->fullname }}"
                            placeholder="Fullname">
                    </div>
                    <small id="fullnameError" class="text-danger"></small>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <input id="username" name="username" class="form-control" type="text" value="{{ $data->username }}"
                            placeholder="Username">
                    </div>
                    <small id="usernameError" class="text-danger"></small>
                </div>
               
                <div class="col-md-6 mb-4">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <input id="email" name="email" class="form-control" type="email" value="{{ $data->email }}"
                            placeholder="Email">
                    </div>
                    <small id="emailError" class="text-danger"></small>
                </div>