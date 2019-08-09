<?php

namespace App\Listeners;

use App\Events\MessageProcessEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageProcessListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ExampleEvent  $event
     * @return void
     */
    public function handle(MessageProcessEvent $event)
    {
        //
        $jsonString = file_get_contents(base_path('storage/logs/message.log'));

        $data = json_decode($jsonString, true);

        if (count($data) == 0) {
            $data = [];
        }

        $data[] = [
            'question'  => $event->question,
            'answer'    => $event->answer,
            'status'    => $event->status,
            'date'      => date('m/d/Y h:i:s a', time())
        ];

        $newJsonString = json_encode($data, JSON_UNESCAPED_UNICODE);

        file_put_contents(base_path('storage/logs/message.log'), $newJsonString);
    }
}
