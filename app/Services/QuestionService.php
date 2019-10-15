<?php
declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;

class QuestionService
{

    public static function getQusetion(int $id = 0)
    {
        $crmHost = config('crm.host');

        $crmApiKey = config('crm.api_key');

        $client = new Client();

        $response = $client->request('GET', $crmHost . '/rest/2525/'. $crmApiKey .'/bot.getlist');

        $body = $response->getBody();

        $content = json_decode($body->getContents());

        $contentData = json_decode($content->result->data);
        
        $index = rand(0, count($contentData->data));
        return $contentData->data[$index];
    }

    public static function getAnswers($questionID)
    {
        $crmHost = config('crm.host');

        $crmApiKey = config('crm.api_key');

        $client = new Client();

        $response = $client->request(
          'GET',
          $crmHost . '/rest/2525/'. $crmApiKey .'/bot.getbyid?ID='.$questionID);

        $body = $response->getBody();

        $content = json_decode($body->getContents());
        $contentData = json_decode($content->result->data);

        return $contentData->data;
    }
}
