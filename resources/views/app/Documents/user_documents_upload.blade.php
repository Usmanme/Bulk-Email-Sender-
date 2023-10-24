@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'document-upload.document-index') }}
@endsection

@section('page-title', 'Upload Documents')

@section('page-vendor')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/responsive.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/tables/datatable/buttons.bootstrap5.min.css">
@endsection

@section('page-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/plugins/forms/form-number-input.min.css">

@endsection

@section('custom-css')
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Upload Documents</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('document-upload.document-index') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('send-email.store') }}" enctype="multipart/form-data" method="POST">

            <div class="card-header">
            </div>

            <div class="card-body">

                @csrf
                
                <div class="mb-3">
                    <label for="formFileMultiple" class="form-label">Attach Your Documents</label>
                    <input class="form-control" type="file" id="formFileMultiple" name="document_name[]" multiple>
                </div>

            </div>

            <div class="card-footer d-flex align-items-center justify-content-end">
                <button type="submit" class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                    <i data-feather='save'></i>
                    Upload
                </button>
                <a href="{{ route('document-upload.document-index') }}"
                    class="btn btn-relief-outline-danger waves-effect waves-float waves-light">
                    <i data-feather='x'></i>
                    Cancel
                </a>
            </div>

        </form>
    </div>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-number-input.min.js"></script>

@endsection

@section('custom-js')
@endsection
