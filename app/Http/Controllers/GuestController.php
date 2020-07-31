<?php

namespace App\Http\Controllers;

use App\Guest;
use App\User;
use App\Event;
use Auth;
use DB;

use App\Notifications\InviteUserToEvent;

use Illuminate\Http\Request;

class GuestController extends Controller
{

    public function cancelParticipation($id){

        $event = Event::find($id);

        if(!$event){
            return redirect('/events')->with('error', 'Evento não encontrado!');
        }

        $guest = Guest::where('event_id', $event->id)->where('user_id', Auth::id())
                    ->first();
        if($guest){
            $guest->status = 'CANCELED';
            $guest->save();

            return response()->json(['msg' => 'Participação cancelada!'], 200);
        }

        return response()->json(['msg' => 'Não foi possivel cancelar sua participação'], 400);

    }

    public function requestParticipation($id){

        $event = Event::find($id);

        $guest = Guest::where('event_id', $event->id)->where('user_id', Auth::id())->first();

        if($guest){
            $guest->status = 'CONFIRMED';
            $guest->save();
        }else{
            $guest = Guest::newGuest(Auth::user(), $event);
        }

        return response()->json(['msg' => 'Sua participação foi confirmada'], 200);
    }

    public function myEvents(){

        $events = Event::with('ownerUser')->with('guests')
            ->join('guests', 'events.id', '=', 'guests.event_id')
            ->where('guests.user_id', Auth::id())
            ->where('guests.status', 'CONFIRMED')
            ->get();

        return view('guests.myevents', compact('events'));
    }


}
