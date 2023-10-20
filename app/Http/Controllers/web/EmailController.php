<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{

    public function index()
    {
        return view('app.email.send-email');

    }

    
    public function store(Request $request)
    {
        
        // Validate the incoming request
        $this->validate($request, [
            'document_name.*' => 'required|mimes:pdf,doc,docx', // Example validation for file types and maximum file size
        ]);

        $files = [];
     
        if ($request->file('document_name')){
            
            foreach($request->file('document_name') as $key => $file)
            {
                
                $fileName = $file->getClientOriginalName();  
               
                $file->move(public_path('uploads'), $fileName);
                $files[]['name'] = $fileName;
            }
        }
        
        
        foreach ($files as $key => $file) {
           
            $file_name = pathinfo($file['name'], PATHINFO_FILENAME); 
            $document = new Document();
            $document->document_path = json_encode($file['name']);
            $document->document_name = json_encode($file_name);
            $document->user_id = Auth::user()->id;
            $document->save();
        }
        
     

        return back()->with('success', 'Files uploaded successfully.');
    }
  



    public function importView()
    {
        return view('app.import-emails.import-email');


    }

    public function importFile(Request $request)
    {
        dd("Here Done");
    }
}
