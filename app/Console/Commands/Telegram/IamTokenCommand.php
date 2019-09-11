<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App\Services\QuestionService;
use App\Question;
use Illuminate\Support\Facades\Artisan;
use App\Services\AuthBotUserService;
use App\Exceptions\TelegramAuthException;

/**
 * Iamtoken command
 *
 * Gets executed when a user first starts using the bot.
 */
class IamTokenCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'iamtoken';
    /**
     * @var string
     */
    protected $description = 'Update iamtoken command';
    /**
     * @var string
     */
    protected $usage = '/iamtoken';
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

        $exitCode = Artisan::call('iamtoken:update');

        $data = [
            'chat_id' => $chat_id,
            'text'    => $exitCode,
        ];

        return Request::sendMessage($data);
    }
}
