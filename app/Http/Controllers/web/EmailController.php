<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;




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


    public function history()
    {
        return view('app.email.history');
    }
}
