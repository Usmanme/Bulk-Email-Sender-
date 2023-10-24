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

    // public function send(Request $request)
    // {

    //     // Validate the incoming request
    //     $this->validate($request, [
    //         'subject' => 'required|string',
    //         'body' => 'required|string',
    //     ]);

    //     try {
    //         Mail::to('usman.islootech@gmail.com')->send(new SendMail($request->subject, $request->body, $request->name));

    //         return back()->withSuccess('Email Sent Successfully!..');
    //     } catch (Exception $ex) {
    //         return back()->withDanger('Something went wrong!' . ' ' . $ex->getMessage());
    //     }
    // }

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
        $email_files = EmailFile::where('user_id', Auth::user()->id)->get();
        return view('app.import-emails.import-email', compact('email_files'));
    }

    public function importFile(Request $request)
    {
        $this->validate($request, [
            'file.*' => 'required',
        ]);
        $files = $request->file('file');
        $user_id = Auth::user()->id;

        foreach($files as $file){

            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();

            $existing_email_files = EmailFile::where('user_id', Auth::user()->id);

            foreach($existing_email_files as $existing_email_file){
                if($originalFileName == $existing_email_file->original_file_name){
                    $existing_email_file->delete();
                }
            }
            
            $email_file = new EmailFile;
            $email_file->user_id = $user_id;
            $email_file->file_extension = $extension;
            $email_file->original_file_name = $originalFileName;
            $email_file->save();

            $fileName = uniqid('document_') . '.' . $extension;
            Storage::disk('public')->put('email_files/' . $fileName, file_get_contents($file));

            $file_id = $email_file->id;

            event(new EmailFileImported($fileName, $file_id));
        }
        return redirect()->back()->with('success', 'Files uploaded successfully.');

    }

    public function delete_file($id)
    {

        $file = EmailFile::find($id);
        $emails = $file->emails;

        foreach ($emails as $email) {
            $email->delete();
        }

        $file_path = 'email_files/' . $file->file_name;

        $file->delete();

        return redirect()->back()->with('success', 'Files deleted successfully.');
    }


    public function download_file($id) {
       
        $file = EmailFile::find($id);
        $user_id = Auth::user()->id;
        $file_user_id = $file->user_id;
        $original_name = $file->original_file_name;
        $extension = $file->file_extension;

        if($user_id != $file_user_id){
            return response()->json(['error' => 'No such File.'], 404);
        }

        if($extension == 'txt'){
            $emails = $file->emails->pluck('email')->toArray();
       
            $emailString = implode(PHP_EOL, $emails);
    
            $headers = [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="' . $original_name . '"',
            ];
            Log::info('line 202');
    
            return response($emailString, 200, $headers);
        }

    }

    public function delete_email($emailId) {
        $email = Email::find($emailId);
        $email->delete();
        return response()->json(['success' => 'Email deleted successfully.'], 200);
        }

    public function history()
    {
        return view('app.email.history');
    }
}
