<?php

namespace App\Events;

class MessageProcessEvent extends Event
{
    public $answer;

    public $question;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $answer, string $question)
    {
        //
        $this->answer = $answer;

        $this->question = $question;
    }
}
