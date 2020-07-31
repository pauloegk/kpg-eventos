<?php

namespace App;

use App\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class Message extends Model
{

    protected $fillable = [
        'title',
        'body',
        'date_string',
        'send_date'
    ];

    protected $hidden = [
    ];


    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }

}
