<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use App\Mail\SendMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Transport\SendmailTransport;

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

    public function importView()
    {
        return view('app.import-emails.import-email');
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
