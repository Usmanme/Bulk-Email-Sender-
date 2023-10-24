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

    public function send(Request $request)
    {
        
        // Validate the incoming request
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

    public function importView()
    {
        $email_files= EmailFile::where('user_id', Auth::user()->id)->get();
        return view('app.import-emails.import-email', compact('email_files'));


    }

    public function importFile(Request $request)
    {
        dd("Here Done");
    }

    public function history()
    {
        return view('app.email.history');
    }
}
