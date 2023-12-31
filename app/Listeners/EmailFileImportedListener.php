<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\EmailFileImported;
use Illuminate\Support\Facades\Auth;
use App\Models\Email;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $file_id= $event->file_id;
        $this->read($fileName, $file_id);
    }

    
    public function read($fileName, $file_id){

        $email_array = array();
        
        $filePath= 'public/email_files/'.$fileName;

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        if ($extension === 'txt') {
            $email_array= $this->read_txt_emails($filePath);
        }
        elseif($extension == 'xlsx' || $extension == 'xls'){
            $email_array= $this->read_xlsx_emails($filePath);
        }
        elseif($extension == 'csv'){
            $email_array= $this->read_csv_emails($filePath);
        }
        else{
            Log::info('File extension not supported.');
        }

        foreach($email_array as $email){
            $email_model = new Email();
            $email_model->email = $email;
            $email_model->email_file_id = $file_id;
            $email_model->user_id = Auth::user()->id;
            $email_model->save();
            Log::info($email);
        }

        Storage::disk('public')->delete('email_files/'.$fileName);

        
    }

    public function read_csv_emails($filePath){
            
            $filePathNew = storage_path('app/'. $filePath);
    
            $file = fopen($filePathNew, "r");
            $email_array = array();
    
            while (($row = fgetcsv($file, 10000, ",")) !== FALSE) {
                foreach ($row as $cell) {
                    $count = substr_count($cell, "@");
            
                    if ($count > 1) {
                        $email_array = $this->multiple_email_in_line($cell, $email_array);
                    } else {
                        $cell = str_replace(' ', '', $cell);
                        $email_array[] = $cell;
                    }
                }
            }
            
    
            fclose($file);
    
            $email_array = $this->extra_handling($email_array);
    
            return $email_array;
    }

    public function read_xlsx_emails($filePath){

        $filePathNew = storage_path('app/'. $filePath);

        $spreadsheet = IOFactory::load($filePathNew);
        $worksheet = $spreadsheet->getActiveSheet();
        $email_array = array();

        foreach ($worksheet->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();

                if($cellValue!=null){
                    $count = substr_count($cellValue, "@");

                    if($count > 1){                
                        $email_array = $this->multiple_email_in_line($cellValue, $email_array);
                    }
                    else{
                        $cellValue = str_replace(' ', '', $cellValue);
                        $email_array[] = $cellValue;
                    }
                }
            }
        }

        $email_array = $this->extra_handling($email_array);
        
        return $email_array;
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

            $email_array = $this->extra_handling($email_array);

            return $email_array;
            
        } else {
            Log::info('File does not exist.');
        }

    }

    public function extra_handling($email_array){

        $email_array = array_filter($email_array, function($value) {
            return $value !== ''; 
        });

        foreach ($email_array as &$email) {
            $email = str_replace([' ', ','], '', $email);
        }
        
        $email_array = array_unique($email_array);
        
        return $email_array;
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
