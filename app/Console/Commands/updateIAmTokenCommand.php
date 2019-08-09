<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\TelegramLog;
use Psr\Log\NullLogger;
use App\Services\YandexCloudAuth;

class updateIAmTokenCommand extends Command
{
    protected $signature = "iamtoken:update";

    protected $description = "";

    public function handle()
    {
        file_put_contents(base_path('storage/iamtoken.key'), YandexCloudAuth::updateIAmToken());
    }
}
