<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Telegram;

class webhookUnsetCommand extends Command
{
    protected $signature = "webhook:unset";

    protected $description = "";

    public function handle()
    {
        $bot_api_key  = '314940430:AAGuoyLM8BsNf1JL-I8Z_pe_J4l9jBorDWY';
        $bot_username = 'onomari_bot';

        try {
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
