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
class GetLogCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'getLog';
    /**
     * @var string
     */
    protected $description = 'Get log file command';
    /**
     * @var string
     */
    protected $usage = '/getlog';
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

        $result = Request::sendDocument([
            'chat_id' => $chat_id,
            'document'   => Request::encodeFile(base_path('storage/logs/message.log')),
        ]);

        return $result;
    }
}
