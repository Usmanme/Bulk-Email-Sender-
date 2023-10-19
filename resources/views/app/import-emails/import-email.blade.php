@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'send-email.importView') }}
@endsection

@section('page-title', 'Import Emails')

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
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Import Emails</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('send-email.importView') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="card-body">
        <form action="{{ route('send-email.importFile') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="file" class="form-control">
            <br>
            <button class="btn btn-primary">Submit</button>
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
