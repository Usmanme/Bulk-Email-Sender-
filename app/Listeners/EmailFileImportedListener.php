<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\EmailFileImported;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class EmailFileImportedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmailFileImported  $event
     * @return void
     */
    public function handle(EmailFileImported $event)
    {
        //
        $fileName= $event->fileName;
        $this->read($fileName);
    }

    
    public function read($fileName){
        
        $filePath= 'public/email_files/'.$fileName;
        $email_array= array();

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if ($extension === 'txt') {
            $email_array= $this->read_txt_emails($filePath);
        }
        else{

            Log::info('unable to handle the file type');
        }

        foreach($email_array as $email){
            Log::info($email);
        }

        
    }

    
    public function read_txt_emails($filePath){

        $email_array = array();
        
        if (Storage::exists($filePath)) {

            $filePathNew = storage_path('app/'. $filePath);
        
            foreach (File::lines($filePathNew) as $line) {

                $count = substr_count($line, "@");

                if($count > 1){                
                    $email_array = $this->multiple_email_in_line($line, $email_array);
                }
                else{
                    $line = str_replace(' ', '', $line);
                    $email_array[] = $line;
                }
            }

            $email_array = array_filter($email_array, function($value) {
                return $value !== ''; 
            });

            foreach ($email_array as &$email) {
                $email = str_replace([' ', ','], '', $email);
            }
            
            $email_array = array_unique($email_array);
            

            return $email_array;
            
        } else {
            Log::info('File does not exist.');
        }

    }

    
    public function multiple_email_in_line($line, $email_array){

        $array_space= explode(" ", $line);

        foreach ($array_space as $element) {
            $array_comma= explode(",", $element);
        
            foreach($array_comma as $subelement){

                $count1 = substr_count($subelement, "@");
                if($count1 > 1){

                    $emails = preg_split("/\.com/", $subelement, -1, PREG_SPLIT_NO_EMPTY);

                    foreach ($emails as $email) {
                            $email_array[] = $email . ".com";
                    }
                }
                else{
                    $email_array[] = $subelement; 
                }
        
            }
        }
        
        return $email_array;
    }


}
