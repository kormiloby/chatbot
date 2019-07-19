<?php

namespace App\Http\Controllers;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Longman\TelegramBot\Request as TelegramRequest;
use Illuminate\Http\Request;
use Psr\Log\NullLogger;
use App\Services\VoiceRecognitionService;
use App\Services\YandexCloudAuth;

class QuestionBotController extends Controller
{
    //
    function __invoke(Request $request) {
        $bot_api_key  = config('bot.bot_api_key');
        $bot_username = config('bot.bot_username');

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
                    $voice_file = $response2->getResult();
                    TelegramRequest::downloadFile($voice_file);
                }

            $token = YandexCloudAuth::getIAmToken();
            $folderId = config('yandex.folderId'); # Идентификатор каталога
            $audioFileName = "/var/www/storage/" . $voice_file->file_path;
            $response = VoiceRecognitionService::recognize($token, $folderId, $audioFileName);
            $response = json_decode($response);

            if (isset($response->result)) {
                $result = TelegramRequest::sendMessage([
                    'chat_id' => $request['message']['chat']['id'],
                    'text'    => $response->result,
                ]);
            }

            return json_encode($response);

        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            // Silence is golden!
            // log telegram errors
            echo $e->getMessage();
        }
    }
}
