<?php

namespace App\Http\Controllers;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Longman\TelegramBot\Request as TelegramRequest;
use Illuminate\Http\Request;
use Psr\Log\NullLogger;
use App\Services\VoiceRecognitionService;
use App\Services\YandexCloudAuth;
use App\Services\AnswerCompareService;
use App\Services\MessageProcessorService\MessageProcessorBuilder;

class QuestionBotController extends Controller
{
    //
    function __invoke(Request $request) {
        $bot_api_key  = config('bot.bot_api_key');
        $bot_username = config('bot.bot_username');

        $commands_paths = [
             '/var/www/app/Console/Commands/Telegram/',
          ];

        try {
            // Create Telegram API object
            $telegram = new Telegram($bot_api_key, $bot_username);
            $telegram->setDownloadPath('/var/www/storage');
            TelegramLog::initialize(new NullLogger(), new NullLogger());
            $telegram->addCommandsPaths($commands_paths);
            // Handle telegram webhook request
            $telegram->handle();

            $messageProcessor = MessageProcessorBuilder::getMessageProcessorInstance($telegram);
            $messageProcessor->process();

            return 200;

        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            // log telegram errors
            echo $e->getMessage();
        }
    }
}
