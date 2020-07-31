<?php

namespace App;

use App\Notifications\InviteUserToEvent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class Guest extends Model
{

    protected $fillable = [
        'status',
        'uuid_invite'
    ];

    protected $hidden = [
        'uuid_invite',
    ];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id', 'id');
    }

    public static function createOwnerEventAsAParticipant($user, $event){
        $guest = new Guest([
            'status' => 'CONFIRMED',
        ]);
        $guest->user()->associate($user);
        $guest->event()->associate($event);
        $guest->save();
    }

    public static function newGuestInvite($user, $event){
        $uuid = (string) Str::uuid();
        $guest = new Guest([
            'uuid_invite' => $uuid,
        ]);
        $guest->user()->associate($user);
        $guest->event()->associate($event);
        $guest->save();
        $user->notify(new InviteUserToEvent($event, $user, $guest));
        return $guest;
    }

    public static function newGuest($user, $event){
        $guest = new Guest(['status'=> 'CONFIRMED']);
        $guest->user()->associate($user);
        $guest->event()->associate($event);
        $guest->save();
        return $guest;
    }

}
