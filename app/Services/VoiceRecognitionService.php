<?php

namespace App\Services;

class VoiceRecognitionService
{
    public static function recognize($token, $folderId, $audioFileName)
    {
        $file = fopen($audioFileName, 'rb');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://stt.api.cloud.yandex.net/speech/v1/stt:recognize?lang=ru-RU&folderId=${folderId}&format=oggopus");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token, 'Transfer-Encoding: chunked']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);

        curl_setopt($ch, CURLOPT_INFILE, $file);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($audioFileName));
        $res = curl_exec($ch);

        return $res;
    }
}
