<?php
declare(strict_types=1);

namespace App\Services;

class QuestionService
{
    public static function getQusetion(int $id = 0): array
    {
        $questions = config('bot.questions');

        if ($id == 0) {
            $id = rand(1, 3);
        }

        $questions = array_filter($questions, function($v) use ($id) {
            return $v['id'] == $id;
        });

        foreach ($questions as $question) {
          $response = $question;
        }

        return $response;
    }
}
