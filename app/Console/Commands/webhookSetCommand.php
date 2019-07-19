<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Psr\Log\NullLogger;

class webhookSetCommand extends Command
{
    protected $signature = "webhook:set";

    protected $description = "";

    public function handle()
    {
      $bot_api_key  = config('bot.bot_api_key');
      $bot_username = config('bot.bot_username');
      $hook_url     = $this->ask('Set webhook url');

      try {
        TelegramLog::initialize(new NullLogger(), new NullLogger());
        // Create Telegram API object
        $telegram = new Telegram($bot_api_key, $bot_username);

        // Set webhook
        $result = $telegram->setWebhook($hook_url);

        if ($result->isOk()) {
            echo $result->getDescription();
        }
      } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        // log telegram errors
        echo $e->getMessage();
      }
    }
}
