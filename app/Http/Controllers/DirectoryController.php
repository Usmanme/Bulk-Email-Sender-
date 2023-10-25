<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailFile;
use App\Models\Email;
use Illuminate\Support\Facades\Auth;
use App\Events\EmailFileImported;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class DirectoryController extends Controller
{
    //

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
        $success_message = 'Files uploaded successfully.'; 

        foreach($files as $file){

            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();

            $existing_email_files = EmailFile::where('user_id', Auth::user()->id);

            foreach($existing_email_files as $existing_email_file){
                if($originalFileName == $existing_email_file->original_file_name){
                    $existing_email_file->delete();
                    $success_message = 'Files uploaded and overwritten successfully.';
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
        return redirect()->back()->with('success', $success_message);

    }

    public function delete_file($id)
    {
        $user_id = Auth::user()->id;

        $file = EmailFile::find($id);
        $emails = $file->emails;
        $file_user_id = $file->user_id;

        if($user_id != $file_user_id){
            return response()->json(['error' => 'No such File.'], 404);
        }

        foreach ($emails as $email) {
            $email->delete();
        }

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
    
            return response($emailString, 200, $headers);
        }
        elseif($extension == 'csv'){
            $emails = $file->emails->pluck('email')->toArray();
            $emails = array_map(function($email){
                return [$email];
            }, $emails);
            array_unshift($emails);
            $file = fopen('php://temp', 'r+');
            foreach ($emails as $email) {
                fputcsv($file, $email);
            }
            rewind($file);
            $csv = stream_get_contents($file);
            fclose($file);
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $original_name . '"',
            ];
            return response($csv, 200, $headers);
        }

    }
    

    public function delete_email($emailId) {
        try{
            
            $email = Email::findorfail($emailId);
            $user_id = Auth::user()->id;
            $email_user_id = $email->email_file->user_id;

            if($user_id != $email_user_id){
                return response()->json(['error' => 'No such Email.'], 404);
            }

            if($email){
                $email->delete();
            }
        }catch(\Exception $e){
            Log::info($e->getMessage());
        }
        return response()->json(['success' => 'Email deleted successfully.'], 200);
    }

}
