# Чат бот с функцией распознования речи

## Artisan команды
 `iamtoken:update` - команда для обновления токена для доступа к сервису распознования речи
 `webhook:set` - прописать домен на который telegram будет отправлять запрос
 `webhook:unset` - удалить текущий домен

## Команды бота
 `/start` - бот присылает сообщение со случайным вопросом
 `/getLog` - бот присылает текстовый фаил с логами в формате json
 `/iamtoken` - обновляется токен для доступа к сервису распознования голоса

## Обработка сообщенй
  Бот получает сообщения от Telegram на основе [webhook](https://core.telegram.org/bots/webhooks).
  Обработка входящих сообщений происходит в контроллере QuestionBotController.php с помощью метода `$messageProcessor->process();`. Это метод одного из классов обраотчика сообщений которые находятся в Services/MessageProcessorService.
  Экземпляр класса мы получаем с помощь класа фабрики Services/MessageProcessorService/MessageProcessorBuilder и его статического метода `MessageProcessorBuilder::getMessageProcessorInstance($telegram);`

### Обработка голосового сообения
  Голосовое сообщение обрабатывается в классе Services/MessageProcessorService/VoiceMessageProcessor.php. Сообщение распознается с помощью класса Services/VoiceRecognitionService.php и сравнивается с ответом в классе Services/AnswerCompareService.php.

## Обработка Команды
  Команды обрабатываются с помощью классов в директории app/Console/Commands/Telegram. Название класса соответствует названию команды.

### Обработка команды `/start`
  Команда `/start` обрабатывается классом app/Console/Commands/Telegram/StartCommand.php. Класс  получает случайный вопрос с помощью статического метода `QuestionService::getQusetion()`.  
