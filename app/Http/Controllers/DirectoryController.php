<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\EmailFile;
use App\Models\Email;
use Illuminate\Support\Facades\Http;
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
        $user_id= Auth::user()->id;
        $this->check_verification_status($user_id);
        return view('app.import-emails.import-email', compact('email_files'));
    }

    public function check_verification_status($id){

        $currentDateTime = Carbon::now();
        
        $user_email_files = EmailFile::where('user_id', $id)
        ->where('verification_end', '<', $currentDateTime)
        ->where('verification', 'verifying')
        ->get();
     

        foreach($user_email_files as $user_email_file){
        
            $api_key= "test_923437f149bad62c093b";
            $base_url = "https://api.emailable.com/v1/";
            $endpoint = "batch";

            $batch_id = $user_email_file->batch_id;

            $response = Http::get($base_url.'/'.$endpoint, [
                'api_key' => $api_key,
                'id' => $batch_id,
            ]);

            $message = $response->json()['message'];
            if($message == "Batch verification completed."){
                $user_email_file->verification = 'Verified';
                $user_email_file->save();
            }

            $response_emails = $response->json()['emails'];
            foreach($response_emails as $response_email){
                $email = Email::where('email', $response_email['email'])->first();
                if($email){
                    $email->status = $response_email['state'];
                    // TODO: add score as well
                    $email->save();
                }
            }

            Log::info(''.$response);
        }
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

        return redirect()->back()->with('success', 'File deleted successfully.');
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

    public function verify($id){
        
        $email_file_id = $id;

        $emails_collection = Email::where('email_file_id', $email_file_id)->get();

        foreach($emails_collection as $email){
            $email->status = 'Verifying';
            $email->save();
        }

        $emails = $emails_collection->pluck('email')->toArray(); 
        $email_String = implode(',', $emails);

        $api_key= "test_923437f149bad62c093b";
        $base_url = "https://api.emailable.com/v1/";
        $endpoint = "batch";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $base_url.$endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'emails='.$email_String.'&api_key='.$api_key.'');

        $response = curl_exec($ch);
        $batch_id = json_decode($response)->id;

        curl_close($ch);

        Log::info(''.$batch_id);

        $emails_count = count($emails);
        $seconds_to_wait = intdiv($emails_count, 30);

        $email_file = EmailFile::find($email_file_id);
        $email_file->verification = 'Verifying';
        $email_file->batch_id = $batch_id;
        $email_file->verification_started = now();
        $email_file->verification_end = now()->addSeconds($seconds_to_wait);
        $email_file->save();

        return redirect()->back()->with('success', 'Verification started successfully.');

    }

}
