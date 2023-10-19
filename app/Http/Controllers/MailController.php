<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Mail\MailNotify;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {

        return view('email.create');
        // $data = [
        //     'subject' => 'This is test Subject',
        //     'body' => 'This is Test Body',
        // ];

        // $emails = [
        //     // ' akhtarsohail550@gmail.com',
        //     // 'akhtarsohail1555@gmail.com',
        //     // 'jellyjordanjj@gmail.com',
        //     // 'jorgjordan1@gmail.com',
        //     // 'rafayzia3690@gmail.com',
        //     // 'daniyalwaris998@gmail.com',
        //     // 'muhammadsamiattk@gmail.com',
        //     'mumarfarooq.at@gmail.com',
        //     'haseeb.aha786@gmail.com',
        //     'haseeb.aha786@yahoo.com',
        //     'haseeb.aha786@aol.com'
        // ];
        // try {
        //     foreach ($emails as $email) {
        //         Mail::to($email)->send(new MailNotify($data));
        //         return response()->json(['message' => 'Great! Check your email']);

        //         // return response()->json(['Great Check Your Email']);
        //     }
        // } catch (Exception $th) {
        //     return response()->json(['Sorry ! Something  Went Wrong']);
        // }
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);


        // $emails=[
        //     'mani.yousaf98@gmail.com',
        //     'usman.islootech@gmail.com'
        // ];
        try {
            // foreach ($emails as $email) {
                // dd($request->all());
                Mail::to('usman.islootech@gmail.com')->send(new MailNotify($request->subject, $request->body, $request->name));
                return response()->json(['message' => 'Great! Check your email']);

                // return response()->json(['Great Check Your Email']);
            // }
        } catch (Exception $th) {
            return response()->json(['Sorry ! Something  Went Wrong']);
        }
    }
}
