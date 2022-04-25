<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use HasFactory;
    protected $fillable = [
        'mail_id',
        'body',
        'from',
        'date',
        'message_id',
        'subject',
        'to',
        'thread_id',
        'label_ids',
        'history_id',
        'user_email',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
   
}
