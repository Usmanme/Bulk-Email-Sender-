<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function documentIndex(){
     return view('app.Documents.user_documents_upload');
    }

}
