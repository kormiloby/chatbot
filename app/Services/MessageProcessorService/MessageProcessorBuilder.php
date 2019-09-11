<?php
declare(strict_types=1);

namespace App\Services\MessageProcessorService;

use Longman\TelegramBot\Telegram;
use App\Services\AuthBotUserService;

/**
 *
 */
class MessageProcessorBuilder
{
    const DEFAULT_MESAGE_PROCESSOR_CLASS_NAME = 'App\Services\MessageProcessorService\MessageProcessor';

    const TEXT_MESAGE_PROCESSOR_CLASS_NAME = 'App\Services\MessageProcessorService\TextMessageProcessor';

    const VOICE_MESAGE_PROCESSOR_CLASS_NAME = 'App\Services\MessageProcessorService\VoiceMessageProcessor';

    public static function getMessageProcessorInstance(Telegram $telegram)
    {
        $input = json_decode($telegram->getCustomInput());

        $className = self::getProcessorClassName($input->message);

        $authSevice = new AuthBotUserService();

        $processorInsatance = new $className($telegram, $authSevice);

        return $processorInsatance;
    }

    protected static function getProcessorClassName(object $messageData): string
    {
        if (isset($messageData->text) && !isset($messageData->entities)) {
            return self::TEXT_MESAGE_PROCESSOR_CLASS_NAME;
        } else if (isset($messageData->voice)) {
            return self::VOICE_MESAGE_PROCESSOR_CLASS_NAME;
        } else {
            return self::DEFAULT_MESAGE_PROCESSOR_CLASS_NAME;
        }
    }
}
