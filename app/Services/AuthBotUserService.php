<?php

namespace App\Services;

use App\AuthUsers;
use Longman\TelegramBot\Request;
use App\Exceptions\TelegramAuthException;
use App\Services\AmmoCrmService;

class AuthBotUserService
{
    public static function isAuth($chatId)
    {
        if (AuthUsers::where('chat_id', $chatId)->first()) {
            return true;
        }

        return false;
    }

    public function verifyCRMid($crmId)
    {
        if (!preg_match("/^([0-9])+$/", $crmId)) {
            throw new TelegramAuthException("Вы ввели не правильный id.");
        }

        if (AuthUsers::where('cms_user_id', $crmId)->first()) {
            throw new TelegramAuthException("Пользователь с таким id уже авторизован.");
        }

        if (!AmmoCrmService::checkUserId($crmId)) {
            throw new TelegramAuthException("Пользователя с таки id не существует в crm системе.");
        }

        return true;
    }

    public function loginInCRM($crmId, $chatId)
    {
        if ($this->verifyCRMid($crmId)) {
            $authUser = new AuthUsers();

            $authUser->chat_id = $chatId;
            $authUser->cms_user_id = $crmId;

            $authUser->save();

            throw new TelegramAuthException("Вы авторизовались. Можете начать работу с помощью команды /start .");
        };

    }
}
