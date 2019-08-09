<?php
declare(strict_types=1);

namespace App\Services\MessageProcessorService;

use App\Question;
use Longman\TelegramBot\Request;
use App\Services\VoiceRecognitionService;
use App\Services\YandexCloudAuth;
use App\Services\QuestionService;
use App\Services\AnswerCompareService;
use App\Events\MessageProcessEvent;

/**
 *
 */
class VoiceMessageProcessor extends MessageProcessor
{
    public function process()
    {
        $chatId = $this->messageData->message->chat->id;
        $question = Question::where([
          'chat_id' => $chatId,
          'status' => 'open'
          ])->first();

        if (is_null($question)) {
          $result = Request::sendMessage([
              'chat_id' => $chatId,
              'text'    => 'Нет активного вопроса. Используйте команду /start',
          ]);

          return $result;
        }

        $voiceMessageFileId = $this->messageData->message->voice->file_id;
        $fileResponse = Request::getFile(['file_id' => $voiceMessageFileId]);

        if ($fileResponse->isOk()) {
            $voiceFile = $fileResponse->getResult();
            Request::downloadFile($voiceFile);
        }

        $token = YandexCloudAuth::getIAmToken();
        $folderId = config('yandex.folderId'); # Идентификатор каталога
        $audioFileName = base_path("storage/") . $voiceFile->file_path;
        $responseRecognitionService = VoiceRecognitionService::recognize($token, $folderId, $audioFileName);

        $responseRecognitionService = json_decode($responseRecognitionService);

        if (!isset($responseRecognitionService->result)) {
          $result = Request::sendMessage([
              'chat_id' => $chatId,
              'text'    => 'Сообщение не распознано',
          ]);

          return $result;
        }

        $questionId = $question->question_id;
        $questions = QuestionService::getQusetion($questionId);
        $comparator = new AnswerCompareService();

        $question->status = 'close';
        $question->save();

        $result = Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => 'Ваш ответ: "' . $responseRecognitionService->result . '"',
        ]);

        foreach( $questions['answer'] as $answer) {
            if ($comparator->calculateFuzzyEqualValue($responseRecognitionService->result, $answer)) {
                $result = Request::sendMessage([
                    'chat_id' => $chatId,
                    'text'    => 'Правильно',
                ]);

                event(new MessageProcessEvent($responseRecognitionService->result, $questions['text'], 'Правильно'));

                return $result;
            }
        }

        event(new MessageProcessEvent($responseRecognitionService->result, $questions['text'], 'Неправильно'));

        $result = Request::sendMessage([
            'chat_id' => $chatId,
            'text'    => 'Неправильно',
        ]);

        return $result;
    }
}
