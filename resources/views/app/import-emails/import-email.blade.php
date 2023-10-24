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


    <div class="card">
        <div class="table-responsive" style="min-height: 500px">
            <div class="m-2">
                <button class="btn btn-primary waves-effect waves-float waves-light" data-bs-target="#import_file"
                    data-bs-toggle="modal">
                    Upload File
                </button>
                <button class="btn btn-primary waves-effect waves-float waves-light ml-2" data-bs-target="#import_file"
                    data-bs-toggle="modal" style="margin-left: 15px;">
                    Import from Drive
                </button>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Emails</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($email_files as $email_file)
                        <tr>
                            <td>
                                {{ $email_file->original_file_name }}
                            </td>
                            <td>
                                {{ $email_file->emails->count() }}
                            </td>
                            <td>
                                {{ $email_file->created_at->toDateString() }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                                      <i data-feather="more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                      <a class="dropdown-item" href="#">
                                        <i data-feather="edit-2" class="me-50"></i>
                                        <span>Emails</span>
                                      </a>
                                      <a class="dropdown-item" href="#">
                                        <i data-feather="edit-2" class="me-50"></i>
                                        <span>Download</span>
                                      </a>
                                      <a class="dropdown-item" href="{{ route('send-email.delete-file', ['id' => $email_file->id]) }}">
                                        <i data-feather="trash" class="me-50"></i>
                                        <span>Delete</span>
                                      </a>
                                    </div>
                                  </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <div class="modal fade" id="import_file" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                {{-- <div class="modal-header bg-transparent">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div> --}}
                {{-- <div class="modal-body pb-5 px-sm-5 pt-50"> --}}
                {{-- <div class="card"> --}}
                <form action="{{ route('send-email.importFile') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-header">
                    </div>

                    <div class="card-body">

                        @csrf

                        <div class="mb-3">
                            <label for="formFileMultiple" class="form-label">Import Email File "txt,xls,xlsx,csv"</label>
                            <input class="form-control" type="file" id="formFileMultiple" name="file">
                        </div>

                    </div>

                    <div class="card-footer d-flex align-items-center justify-content-end">
                        <button type="submit"
                            class="btn btn-relief-outline-success waves-effect waves-float waves-light me-1">
                            <i data-feather='save'></i>
                            Upload
                        </button>
                        <a class="btn btn-relief-outline-danger waves-effect waves-float waves-light" type="button"
                            class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i data-feather='x'></i>
                            Cancel
                        </a>
                    </div>

                </form>
                {{-- </div> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>
@endsection

@section('vendor-js')
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-number-input.min.js"></script>

@endsection

@section('custom-js')
@endsection
