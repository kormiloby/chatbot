<?php

namespace App\Http\Controllers;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Longman\TelegramBot\Request as TelegramRequest;
use Illuminate\Http\Request;
use Psr\Log\NullLogger;

class QuestionBotController extends Controller
{
    //
    function hook(Request $request) {
        $bot_api_key  = '314940430:AAGuoyLM8BsNf1JL-I8Z_pe_J4l9jBorDWY';
        $bot_username = 'onomari_bot';

        try {
            // Create Telegram API object
            $telegram = new Telegram($bot_api_key, $bot_username);
            $telegram->setDownloadPath('/var/www/storage');
            TelegramLog::initialize(new NullLogger(), new NullLogger());

            // Handle telegram webhook request
            $telegram->handle();
            // echo $telegram->update->getUpdateType();
            $voiceMessageFileId = $request['message']['voice']['file_id'];
            $response2 = TelegramRequest::getFile(['file_id' => $voiceMessageFileId]);
                if ($response2->isOk()) {
                    /** @var File $photo_file */
                    $photo_file = $response2->getResult();
                    TelegramRequest::downloadFile($photo_file);
                }

            $result = TelegramRequest::sendMessage([
                'chat_id' => $request['message']['chat']['id'],
                'text'    => 'Your utf8 text 😜 ...',
            ]);
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            // log telegram errors
            echo $e->getMessage();
        }
    }
}
