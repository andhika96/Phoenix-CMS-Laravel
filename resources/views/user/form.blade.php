@extends('themes.default.admin.admin_layout')

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
            <div class="row mb-4">
                <div class="col">
                    <label class="form-label">Variant Model Name</label>
                    <div class="input-group">
                        <input id="name" name="name" class="form-control" type="text" value="{{ $data->name }}"
                            placeholder="Variant">
                    </div>
                    <small id="nameError" class="text-danger"></small>
                </div>

                <div class="col">
                    <label class="form-label">Model</label>
                    <div class="form-group mb-3" id="model_idFormGroup">
                        <select class="form-control" name="model_id" id="model_choices" placeholder="Model">
                        </select>
                    </div>
                    <small id="model_idrror" class="text-danger"></small>
                </div>
            </div>
        </form>

    </div>
@endsection
