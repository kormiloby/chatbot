<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Telegram;
use App\Services\MessageProcessorService\MessageProcessorBuilder;

class QuestionBotController extends Controller
{
    //
    function __invoke(Telegram $telegram) {

        try {

            $telegram->handle();

            $messageProcessor = MessageProcessorBuilder::getMessageProcessorInstance($telegram);
            $messageProcessor->process();

            return 200;

        } catch (TelegramException $e) {
            // Silence is golden!
            // log telegram errors
            echo $e->getMessage();
        }
    }
}
