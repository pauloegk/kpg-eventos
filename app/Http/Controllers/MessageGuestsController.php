<?php

namespace App\Http\Controllers;

use App\User;
use App\Event;
use App\Message;
use Auth;
use Carbon\Carbon;

use App\Notifications\MessageToGuests;

use Illuminate\Http\Request;

class MessageGuestsController extends Controller
{

    public function send(Request $request)
    {
        $event = Event::find($request->event_id);

        $message = new Message();
        $message->title = $request->title;
        $message->body = $request->body;
        $message->event()->associate($event);

        if($request->item == "now") {

            $message->delivered = 'YES';
            $message->send_date = Carbon::now();
            $message->save();

            $users = User::all();

            foreach($users as $user) {
                $user->notify(new MessageToGuests($message));
            }

            return response()->json('Mail sent.', 201);

        } else {

            $message->date_string = date("Y-m-d", strtotime($request->send_date));

            $message->save();

            return response()->json('Notification will be sent later.', 201);
        }
    }

}
