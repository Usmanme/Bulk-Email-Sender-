@extends('app.layout.layout')

@section('seo-breadcrumb')
    {{ Breadcrumbs::view('breadcrumbs::json-ld', 'directory.importView') }}
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
<style>
    .smooth-remove {
  opacity: 0;
  transform: scale(0);
  transition: opacity 0.5s, transform 0.5s;
  /* You can adjust the duration and transition properties as needed */
}

</style>
@endsection

@section('breadcrumbs')
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-start mb-0">Import Emails</h2>
                <div class="breadcrumb-wrapper">
                    {{ Breadcrumbs::render('directory.importView') }}
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
                            <td id="email_count_{{$email_file->id}}">
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
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#email_list{{$email_file->id}}">
                                            <i data-feather="list" class="me-50"></i>
                                            <span>Emails</span>
                                        </a>
                                        
                                      <a class="dropdown-item" href="{{ route('directory.download-file', ['id' => $email_file->id]) }}">
                                        <i data-feather="download" class="me-50"></i>
                                        <span>Download</span>
                                      </a>
                                      <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#delete_file{{$email_file->id}}">
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
                <form action="{{ route('directory.importFile') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-header">
                    </div>

                    <div class="card-body">

                        @csrf

                        <div class="mb-3">
                            <label for="filePond" class="form-label">Import Email File "txt,xls,xlsx,csv"</label>
                            <input class="form-control" type="file" id="filePond" name="file[]" multiple>
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
            </div>
        </div>
    </div>

    @foreach($email_files as $email_file)
    <div class="modal fade" id="delete_file{{$email_file->id}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
          <div class="modal-content">
            <div class="modal-header bg-transparent">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-1 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h5 class="mb-1">Are you sure you wish to delete {{$email_file->original_file_name}}?</h4>
                  </div>
                <div>
                    <div class="text-center mb-2">
                        <a href="{{ route('directory.delete-file', ['id' => $email_file->id]) }}">
                            <button class="btn btn-danger mr-2">
                                Delete
                            </button>
                        </a>
                        <button class="btn btn-secondary" data-bs-dismiss="modal" style="margin-left: 15px;">
                            Cancel
                        </button>
                    </div>
                    
                    </div>
                </div>
              
              
            </div>
          </div>
        </div>
      </div>
    @endforeach

    @foreach($email_files as $email_file)
    <div class="modal fade" id="email_list{{$email_file->id}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
          <div class="modal-content">
            <div class="modal-header bg-transparent">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
              <div class="text-center mb-2">
                <h4 class="mb-1">{{$email_file->original_file_name}}</h4>
              </div>
              <table class="table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($email_file->emails as $email)
                        <tr class="email-row" id="email-row-id-{{$email->id}}">
                            <td>
                                {{ $email->email }}
                            </td>
                            <td>
                                <a href="javascript:void(0);" onclick="deleteEmail({{$email->id}}, {{$email_file->id}})">
                                    <i data-feather="trash" class="me-50"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    @endforeach
@endsection

@section('vendor-js')
@endsection

@section('page-js')
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-number-input.min.js"></script>

@endsection

@section('custom-js')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.30.4/filepond.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/filepond/4.30.4/filepond.css" rel="stylesheet">
<script>
    function deleteEmail(emailId, emailFileId) {
        const elementToRemove = document.getElementById(`email-row-id-${emailId}`);

        elementToRemove.classList.add('smooth-remove');
        elementToRemove.addEventListener('transitionend', function () {
        elementToRemove.remove();
        // Make an AJAX request to the Laravel route
        axios.delete(`/directory/delete-email/${emailId}`)
            .then(response => {
                // Handle the response from the server, e.g., show a success message
                console.log('Email deleted successfully');
                
                const emailCountElement = document.getElementById(`email_count_${emailFileId}`);
                emailCountElement.innerHTML = parseInt(emailCountElement.innerHTML) - 1;
                
                });            
            })
    }




    const fileDropArea = document.getElementById('import_file');
    const fileInput = document.getElementById('filePond');

    // Prevent the default behavior for drag-and-drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileDropArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    // Highlight the drop area when a file is dragged over it
    fileDropArea.addEventListener('dragenter', () => {
        fileDropArea.classList.add('dragover');
    });

    fileDropArea.addEventListener('dragleave', () => {
        fileDropArea.classList.remove('dragover');
    });

    // Handle the drop event
    fileDropArea.addEventListener('drop', (e) => {
        fileDropArea.classList.remove('dragover');

        // Get the dropped files
        const files = e.dataTransfer.files;

        // Update the FilePond input's files property with the dropped files
        fileInput.files = files;
    });

</script>

@endsection
