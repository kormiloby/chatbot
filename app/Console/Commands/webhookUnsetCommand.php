<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Psr\Log\NullLogger;

class webhookUnsetCommand extends Command
{
    protected $signature = "webhook:unset";

    protected $description = "";

    public function handle()
    {
        $bot_api_key  = config('bot.bot_api_key');
        $bot_username = config('bot.bot_username');

        try {
            TelegramLog::initialize(new NullLogger(), new NullLogger());
            // Create Telegram API object
            $telegram = new Telegram($bot_api_key, $bot_username);
            // Delete webhook
            $result = $telegram->deleteWebhook();
            
            if ($result->isOk()) {
                echo $result->getDescription();
            }
        } catch (Longman\TelegramBot\Exception\TelegramException $e) {
            echo $e->getMessage();
        }
    }
}
