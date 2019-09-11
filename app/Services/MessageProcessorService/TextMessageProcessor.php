<?php
declare(strict_types=1);

namespace App\Services\MessageProcessorService;

use App\Services\AuthBotUserService;
use App\Exceptions\TelegramAuthException;
/**
 *
 */
class TextMessageProcessor extends MessageProcessor
{
    public function process()
    {
        $chatId = $this->messageData->message->chat->id;

        $messageText = $this->messageData->message->text;

        if (!AuthBotUserService::isAuth($chatId)) {
            $this->authSevice->loginInCRM($messageText, $chatId);
        }

        return [];
    }


}
