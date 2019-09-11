<?php
declare(strict_types=1);

namespace App\Services\MessageProcessorService;

use Longman\TelegramBot\Telegram;
use App\Exceptions\TelegramAuthException;
use App\Services\AuthBotUserService;

/**
 *
 */
class MessageProcessor implements MessageProcessorInterface
{
    protected $messageData;

    protected $authSevice;

    function __construct(Telegram $telegram, AuthBotUserService $authSevice)
    {
        $this->messageData = json_decode($telegram->getCustomInput());

        $this->authSevice = $authSevice;
    }

    public function process()
    {

    }
}
