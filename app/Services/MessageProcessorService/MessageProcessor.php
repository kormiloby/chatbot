<?php
declare(strict_types=1);

namespace App\Services\MessageProcessorService;

use Longman\TelegramBot\Telegram;


/**
 *
 */
class MessageProcessor implements MessageProcessorInterface
{
    protected $messageData;

    function __construct(Telegram $telegram)
    {
        $this->messageData = json_decode($telegram->getCustomInput());
    }

    public function process()
    {

    }
}
