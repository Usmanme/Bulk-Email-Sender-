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

    public function store(Request $request)
    {

        dd($request->all());
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
