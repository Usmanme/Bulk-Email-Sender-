<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\EmailFile;
use App\Models\Email;
use Illuminate\Support\Facades\Auth;

use App\Events\EmailFileImported;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class EmailController extends Controller
{

    public function index()
    {
        return view('app.email.send-email');

    }

    public function send(Request $request)
    {


        $this->validate($request, [
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        try {
            Mail::to('usman.islootech@gmail.com')->send(new SendMail($request->subject, $request->body, $request->name));

            return back()->withSuccess('Email Sent Successfully!..');
        } catch (Exception $ex) {
            return back()->withDanger('Something went wrong!' . ' ' . $ex->getMessage());
        }
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
        $email_files= EmailFile::where('user_id', Auth::user()->id)->get();
        return view('app.import-emails.import-email', compact('email_files'));


    }

    public function importFile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
        ]);
        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $originalFileName = $file->getClientOriginalName();
        $fileName = uniqid('document_') . '.' . $extension;
        Storage::disk('public')->put('email_files/'.$fileName, file_get_contents($file));

        $email_file = new EmailFile;
        $email_file->user_id = Auth::user()->id;
        $email_file->file_name = $fileName;
        $email_file->file_extension = $extension;
        $email_file->original_file_name = $originalFileName;
        $email_file->save();

        $file_id = $email_file->id;

        event (new EmailFileImported($fileName, $file_id));
        return redirect()->back()->with('success', 'Files uploaded successfully.');

    }

    public function history()
    {
        return view('app.email.history');

    }

}
