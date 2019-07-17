<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Telegram;

class webhookSetCommand extends Command
{
    protected $signature = "webhook:set";

    protected $description = "";

    public function handle()
    {
      $bot_api_key  = '314940430:AAGuoyLM8BsNf1JL-I8Z_pe_J4l9jBorDWY';
      $bot_username = 'onomari_bot';
      $hook_url     = 'https://47de38ed.ngrok.io/question_bot';

      try {
        // Create Telegram API object
        // $telegram = new Telegram($bot_api_key, $bot_username);

        // Set webhook
        $result = $telegram->setWebhook($hook_url);
        echo $result;
        if ($result->isOk()) {
            echo $result->getDescription();
        }
      } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        // log telegram errors
        // echo $e->getMessage();
      }
    }
}
