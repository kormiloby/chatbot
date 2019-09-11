<?php
namespace App\Services;

use GuzzleHttp\Client;

class AmmoCrmService
{
    public static function checkUserId($userId) {
        $jsonString = file_get_contents(base_path('storage/crm_id.txt'));
        $data = json_decode($jsonString, true);

        foreach ($data as $id) {
            if ($id == $userId) {
                return true;
            }
        }

        return true;
    }

    public static function sendResult($userId, $questionId, $note, $answerText) {
        $client = new Client();

        $response = $client->request('GET', "https://crmtest.for-est.ru/rest/2525/hly9vv9jbexm9inm/bot.setanswer?USER_ID=$userId&QUESTION_ID=$questionId&NOTE=$note&ANSWER_TEXT=$answerText");

        $body = $response->getBody();
        $content = json_decode($body->getContents());

        if ($content->result->status == "success") {
            return true;
        }

        return false;
    }
}
