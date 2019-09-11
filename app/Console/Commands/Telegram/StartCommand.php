<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App\Services\QuestionService;
use App\Question;
use App\Services\AuthBotUserService;
use App\Exceptions\TelegramAuthException;
/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';
    /**
     * @var string
     */
    protected $description = 'Start command';
    /**
     * @var string
     */
    protected $usage = '/start';
    /**
     * @var string
     */
    protected $version = '1.1.0';
    /**
     * @var bool
     */
    protected $private_only = true;
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        if (!AuthBotUserService::isAuth($chat_id)) {
            throw new TelegramAuthException('Вы не авторизованы. Для авторизации отправте id в CRM системе.');
        }

        $question = QuestionService::getQusetion();

        if (!isset($question->ID) || !isset($question->QUESTION)) {
            return false;
        }

        $questionId = $question->ID;
        $text = $question->QUESTION;

        $oldQuestions = Question::where([
          'chat_id' => $chat_id,
          'status' => 'open'
          ])->get();

        if (count($oldQuestions) > 0) {
          foreach ($oldQuestions as $question) {
              $question->status = 'close';
              $question->save();
          }
        }

        Question::create([
            'status'      => 'open',
            'question_id' => $questionId,
            'chat_id'     => $chat_id
          ]);

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
