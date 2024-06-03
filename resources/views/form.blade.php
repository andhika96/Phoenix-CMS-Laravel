@extends('themes.default.admin.admin_layout')
@section('title')
    Edit Data
@endsection
@section('content')
    <div class="arv6-box p-4" id="ph-app">
        <div class="" id="ph-form-submit">
            <form action="{{ $editMode ?? false ? "{$urls['update']}/{$data?->id}" : $urls['store'] }}" method="post"
                reset="true" @submit.prevent="submitData" ref="formHTML">
                <div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-3 border-bottom">
                    <div class="h5">
                        Edit {{-- {{ $data?->username ?? ($data?->name ?? ($data?->title ?? 'A')) }} --}} Data
                    </div>
                </div>
                <div class="row">
                    @php
                        $modelAttr = $data->toArray();
                        if (Arr::hasAny($modelAttr, ['status', 'is_active'])) {
                            $is_active = Arr::pull($modelAttr, 'is_active', null);
                            $status = Arr::pull($modelAttr, 'status', null);

                            if (isset($is_active)) {
                                $modelAttr['is_active'] = $is_active;
                            }
                            if (isset($status)) {
                                $modelAttr['status'] = $status;
                            }
                        }

                    @endphp
                    @foreach ($modelAttr as $key => $value)
                        @if (in_array($key, ['id', 'uuid', 'updated_at', 'created_at']))
                            @continue
                        @endif
                        <div class="col-12 col-md-6 mb-4">
                            @if (in_array($key, ['status', 'is_active']))
                                <label class="form-check-label" for="{{ $key }}">{{ $key }}</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input w-5 h-4" type="checkbox" role="switch"
                                        id="{{ $key }}" {{ $value ? 'checked' : '' }}>
                                </div>
                            @else
                                <label class="form-label">{{ $key }}</label>
                                <div class="input-group">
                                    <input id="{{ $key }}" name="{{ $key }}" class="form-control"
                                        type="{{ in_array($key, ['email'])
                                            ? 'email'
                                            : (in_array($key, ['recovery_code_duration'])
                                                ? 'datetime-local'
                                                : 'text') }}"
                                        value="{{ $value }}" placeholder="{{ $key }}"
                                        {{ in_array($key, ['recovery_code_duration']) ? "step=1" : '' }}>
                                </div>
                            @endif
                            <small id="{{ $key }}Error" class="text-danger"></small>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 mt-lg-0">
                    <div class="d-flex justify-content-end">
                        <div class="col-auto">
                            <a href="{{ url()->previous() }}" class="btn btn-default">Cancel</a>
                        </div>

                        <div class="col-auto">
                            <button type="submit" class="btn btn-success btn-submit"><i
                                class="fad fa-save me-2"></i>Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
