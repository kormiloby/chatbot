<?php
namespace App\Services;

use GuzzleHttp\Client;

class AmmoCrmService
{

    public static function checkUserId($userId) {
        $crmHost = config('crm.host');

        $crmApiKey = config('crm.api_key');

        $client = new Client();

        $apiUrl = $crmHost . "/rest/2525/" . $crmApiKey . "/bot.getbyid?USER_ID=" . $userId;

        $response = $client->request('GET', $apiUrl);

        $body = $response->getBody();
        $content = json_decode($body->getContents());

        if ($content->result->status === 'success') {
            return true;

        }

        return false;
    }

    public static function sendResult($userId, $questionId, $note, $answerText) {
        $crmHost = config('crm.host');

        $crmApiKey = config('crm.api_key');

        $client = new Client();

        $response = $client->request('GET', $crmHost . "/rest/2525/". $crmApiKey ."/bot.setanswer?USER_ID=$userId&QUESTION_ID=$questionId&NOTE=$note&ANSWER_TEXT=$answerText");

        $body = $response->getBody();
        $content = json_decode($body->getContents());

        if ($content->result->status == "success") {
            return true;
        }

        return false;
    }
}
