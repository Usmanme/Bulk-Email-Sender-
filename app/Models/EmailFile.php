<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailFile extends Model
{
    use HasFactory;
    protected $table = 'email_files';
    protected $fillable = ['user_id', 'file_name', 'file_extension'];
    

    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
