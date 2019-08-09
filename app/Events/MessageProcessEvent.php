<?php

namespace App\Events;

class MessageProcessEvent extends Event
{
    public $answer;

    public $question;

    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $answer, string $question, string $status)
    {
        //
        $this->answer = $answer;

        $this->question = $question;

        $this->status = $status;
    }
}
