<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Psr\Log\NullLogger;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Telegram::class, function ($app) {

            $bot_api_key  = config('bot.bot_api_key');
            $bot_username = config('bot.bot_username');

            $commands_paths = [
                 base_path('app/Console/Commands/Telegram/'),
              ];

            $telegram = new Telegram($bot_api_key, $bot_username);
            $telegram->setDownloadPath(base_path('storage'));
            $telegram->addCommandsPaths($commands_paths);

            TelegramLog::initialize(new NullLogger(), new NullLogger());

            return $telegram;
        });
    }
}
