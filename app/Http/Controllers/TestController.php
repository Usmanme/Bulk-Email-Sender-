<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Email;

class TestController extends Controller
{
    //
    public function batch(){

        $email_file_id = $id;

        $emails_collection = Email::where('email_file_id', $email_file_id)->get();
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

        curl_close($ch);

        Log::info(''.$response);

    }

    // public function batch_status(){
    //     $api_key= "653b6cea4cfed75a0b8d0055";
    //     $base_url = "https://api.emailable.com/v1/";
    //     $endpoint = "batch";

    //     $batch_id = '653b59bd78f2f2598a7f396a';

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $base_url.$endpoint);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: application/x-www-form-urlencoded',
    //     ]);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, 'api_key='.$api_key.'$id='.$batch_id.'');

    //     $response = curl_exec($ch);

    //     curl_close($ch);

    //     Log::info(''.$response);
    // }

    public function batch_status_2(){

    $api_key= "test_923437f149bad62c093b";
    $base_url = "https://api.emailable.com/v1/";
    $endpoint = "batch";

    $batch_id = '653b6cea4cfed75a0b8d0055';

    $response = Http::get($base_url.'/'.$endpoint, [
        'api_key' => $api_key,
        'id' => $batch_id,
    ]);

    Log::info(''.$response);


    }
}
