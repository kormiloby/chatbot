<?php
declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;

class QuestionService
{
    public static function getQusetion(int $id = 0)
    {
        $client = new Client();

        $response = $client->request('GET', 'https://crmtest.for-est.ru/rest/2525/hly9vv9jbexm9inm/bot.getlist');

        $body = $response->getBody();

        $content = json_decode($body->getContents());

        $contentData = json_decode($content->result->data);
        $index = rand(0, count($contentData->data));
        return $contentData->data[$index];
    }

    public static function getAnswers($questionID)
    {
      $client = new Client();

      $response = $client->request(
        'GET',
        'https://crmtest.for-est.ru/rest/2525/hly9vv9jbexm9inm/bot.getbyid?ID='.$questionID);

      $body = $response->getBody();

      $content = json_decode($body->getContents());
      $contentData = json_decode($content->result->data);

      return $contentData->data;
    }
}
