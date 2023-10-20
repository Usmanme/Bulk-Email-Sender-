<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\EmailFileImported;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $this->validate($request, [
            'file' => 'required',
        ]);
        $file = $request->file('file');
        $fileName = uniqid('document_') . '.' . $file->getClientOriginalExtension();
        Storage::disk('public')->put($fileName, file_get_contents($file));

        event (new EmailFileImported($fileName));
        return redirect()->back()->with('success', 'Files uploaded successfully.');

    }

    public function history()
    {
        return view('app.email.history');

    }

}
