<?php

namespace App;

use App\Guest;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date_event',
    ];


    public function ownerUser()
    {
        return $this->belongsTo('App\User', 'owner_user_id', 'id');
    }

    public function guests()
    {
        return $this->hasMany('App\Guest');
    }

    public static function newEvent($request, $user)
    {
        $event = new Event([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'date_event' => $request->get('date_event'),
        ]);

        $event->ownerUser()->associate($user)->save();

        Guest::createOwnerEventAsAParticipant($user, $event);

        return $event;
    }


}
