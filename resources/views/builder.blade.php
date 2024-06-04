@extends('themes.default.admin.admin_layout')
@section('title')
    Form Builder
@endsection
@section('content')
    <div class="arv6-box p-4" id="ph-builder-app">
        <div class="" id="ph-form-submit">
            <form action="{{-- {{ $editMode ?? false ? "{$urls['update']}/{$data?->id}" : $urls['store'] }} --}}" method="post"
                reset="true" @submit.prevent="submitData" ref="formHTML">
                <div class="arv6-header d-lg-flex justify-content-lg-between align-items-lg-center pb-3 mb-3 border-bottom">
                    <div class="h5">
                        Edit Data
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label class="form-label" for="title">Form Title</label>
                        
                        
                        <small id="titleError" class="text-danger"></small>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="model">Model/Table</label>
                        <select class="form-select mb-3" aria-label="model" @change="fetchModelColumn($event.target.value)">
                            <option selected>Select a model</option>
                            @foreach ($models as $key => $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                        
                        <small id="modelError" class="text-danger"></small>
                    </div>
                </div>

                <div class="ph-model-columns" data-url="{{ url('awesome_admin/tools/model/columns') }}">
                </div>

                <div class="d-flex">
                    <div class="">
                        <div>
                            <div>
                                <div v-for="(info, index) in responseData" :key="info.name">
                                    @{{ info }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 mt-lg-0" >
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