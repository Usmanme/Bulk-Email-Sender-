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

    public function importView()
    {
        $email_files = EmailFile::where('user_id', Auth::user()->id)->get();
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
        Storage::disk('public')->put('email_files/' . $fileName, file_get_contents($file));

        $email_file = new EmailFile;
        $email_file->user_id = Auth::user()->id;
        $email_file->file_name = $fileName;
        $email_file->file_extension = $extension;
        $email_file->original_file_name = $originalFileName;
        $email_file->save();

        $file_id = $email_file->id;

        event(new EmailFileImported($fileName, $file_id));
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
        Storage::disk('public')->delete($file_path);

        $file->delete();

        return redirect()->back()->with('success', 'Files deleted successfully.');
    }

    public function download_file($id)
    {
        $file = EmailFile::find($id);
        $filename = $file->file_name;
        $original_name= $file->original_file_name;

        $filePath = 'email_files/' . $filename;

        // Check if the file exists
        if (Storage::disk('public')->exists($filePath)) {
            $file = Storage::disk('public')->get($filePath);

            // Set the appropriate HTTP headers for the download
            $headers = [
                'Content-Type' => Storage::disk('public')->mimeType($filePath),
                'Content-Disposition' => 'attachment; filename="' . $original_name . '"',
            ];

            // Return the file as a response
            return response($file, 200, $headers);
        } else {
            // Handle the case where the file does not exist
            return response()->json(['error' => 'File not found'], 404);
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
