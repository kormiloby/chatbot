<?php

namespace App\Http\Controllers;

use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use App\Services\MessageProcessorService\MessageProcessorBuilder;
use App\Services\AuthBotUserService;
use App\Exceptions\TelegramAuthException;
use Longman\TelegramBot\Request;

class QuestionBotController extends Controller
{
    protected $messageProcessor;
    //
    function __invoke(Telegram $telegram) {

        try {
            $telegram->handle();

            $this->messageProcessor = MessageProcessorBuilder::getMessageProcessorInstance($telegram);
            $this->messageProcessor->process();

            return 200;
        } catch (TelegramException $e) {
            // Silence is golden!
            // log telegram errors
            echo $e->getMessage();
        } catch (TelegramAuthException $e) {
            $chatId = json_decode($telegram->getCustomInput())->message->chat->id;

            Request::sendMessage([
                'chat_id' => $chatId,
                'text'    => $e->getMessage(),
            ]);
        }
    }
}
