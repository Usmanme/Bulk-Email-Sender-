<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $table = 'emails';
    protected $fillable = ['email', 'user_id', 'file_id'];
    
    protected $dates = ['deleted_at'];

    public function email_file()
    {
        return $this->belongsTo(EmailFile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
