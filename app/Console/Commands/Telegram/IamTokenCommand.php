<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
use App\Services\QuestionService;
use App\Question;
use Illuminate\Support\Facades\Artisan;

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

        $exitCode = Artisan::call('iamtoken:update');

        $data = [
            'chat_id' => $chat_id,
            'text'    => $exitCode,
        ];

        return Request::sendMessage($data);
    }
}
