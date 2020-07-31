<?php

namespace App\Console\Commands;

use App\Message;

use Carbon\Carbon;
use App\Jobs\SendMailJob;
use Illuminate\Console\Command;
use App\Mail\NotificationGuestsMail;

use App\Notifications\MessageToGuests;

class NotifyGuestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:guests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification to guests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = date("Y-m-d", strtotime(Carbon::now()));

        $messages = Message::where('date_string', $now)->where('delivered', 'NO')->get();
        if($messages !== null){
            $messages->each(function($message) {
                $event = $message->event;
                foreach ($event->guests as $guest) {
                    $user = $guest->user;
                    $user->notify(new MessageToGuests($message));
                }
                $message->delivered = 'YES';
                $message->save();
            });

        }
    }
}
