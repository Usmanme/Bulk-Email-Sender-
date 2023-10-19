<!DOCTYPE html>
<html>

<head>
    <title>BULK EMAIL SENDER</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-4">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header text-center font-weight-bold">
                BULK EMAIL SENDER
            </div>
            <div class="card-body">
                <form name="add-blog-post-form" id="add-blog-post-form" method="post" action="{{ route('store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="subject">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label for="subject">Email Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" required="">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="body" id="body" name="body" class="form-control" required=""></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Attach Your Documents</label>
                        <input class="form-control" type="file" id="formFileMultiple" name="documents[]"  multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
