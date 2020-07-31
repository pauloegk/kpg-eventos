<?php

namespace App\Http\Controllers;

use App\Event;
use App\Guest;
use App\User;
use Auth;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;

use App\Notifications\InviteUserToEvent;
use App\Notifications\EventCanceled;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all()->sortByDesc("created_at");

        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'date_event'=>'required'
        ]);

        $event = Event::newEvent($request, Auth::user());

        if(!$event){
            return view('events.create')->withErrors(["error"=>"Ocorreu um erro ao criar seu evento"]);
        }
        return redirect('/events')->with('success', 'Evento criado com sucesso!');
    }

    public function create()
    {
        return view('events.create');
    }

    public function show($id)
    {
        $event = Event::where('id', $id)->with('ownerUser')->first();

        $guest = Guest::where('user_id', Auth::id())->where('event_id', $event->id)->where('status', 'CONFIRMED')->first();
        $event->userLoggedConfirmed = $guest ? true : false;
        $event->userLoggedIsOwnerEvent = $event->owner_user_id == Auth::id();

        return view('events.show', ["event"=> $event]);
    }

    public function edit($id)
    {
        $event = Event::find($id);
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
            'date_event'=>'required'
        ]);

        $event = Event::find($id);
        $event->name =  $request->get('name');
        $event->description = $request->get('description');
        $event->date_event = $request->get('date_event');
        $event->save();

        return redirect('/events')->with('success', 'Evento atualizado!');
    }

    public function cancelEvent($id)
    {
        $event = Event::find($id);

        if($event->canceled){
            return response()->json(['msg' => 'Evento já se encontra cancelado'], 400);
        }

        if($event->owner_user_id == Auth::id()) {
            $event->canceled = true;
            $event->save();

            // $event->guests()->update(['status' => 'CANCELED']);

            foreach ($event->guests as $guest) {
                $user = $guest->user;
                $user->notify(new EventCanceled($user, $event));
            }

            return response()->json(['msg' => 'Evento cancelado com sucesso']);
        }
        return response()->json(['msg' => 'Não foi possivel cancelar o evento'], 400);
    }

    public function sendInvite(Request $request, $id)
    {
        $request->validate([
            'email'=>'required',
        ]);

        $event = Event::find($id);
        $user = User::where('email', $request->get('email'))->first();

        if($user && $event){

            $guest = Guest::where('event_id', $event->id)->where('user_id', $user->id)->first();

            if($guest){
                if($guest->status === "CONFIRMED"){
                    return response()->json(['msg' => 'Usuário já confirmou presença!'], 400);
                }else{
                    $user->notify(new InviteUserToEvent($event, $user, $guest));
                    return response()->json(['msg' => 'Convite foi reenviado ao usuário!']);
                }
            } else {
                Guest::newGuestInvite($user, $event);
            }
            return response()->json(['msg' => 'Usuário foi convidado com sucesso!']);
        }

        return response()->json(['msg' => 'Usuário ou evento não localizado'], 400);

    }

    public function confirmInvite(Request $request, $id)
    {
        $request->validate([
            'code'=>'required',
        ]);

        $event = Event::find($id);

        if(!$event){
            return redirect('/events')->with('error', 'Evento não encontrado!');
        }

        $code = $request->input('code');

        $guest = Guest::where('event_id', $event->id)
                    ->where('uuid_invite', $code)
                    ->first();

        if($guest && $guest->uuid_invite == $code){

            $guest->status = 'CONFIRMED';
            $guest->save();

            return redirect('/events')->with('success', 'Sua presença foi confirmada com sucesso');
        }
        return redirect('events')->with('error', 'O link informado não é mais válido');
    }

    public function getGuests($id){
        return Guest::with('user')->where('event_id', $id)->where('status', 'CONFIRMED')->get();
    }

}
