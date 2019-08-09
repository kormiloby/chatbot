<?php

namespace App\Services;

use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Core\Util\JsonConverter;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Algorithm\PS256;
use Jose\Component\Signature\Serializer\CompactSerializer;

class YandexCloudAuth
{
    public static function updateJWTToken()
    {
        $service_account_id = config('yandex.service_account_id');
        $key_id = config('yandex.key_id');

        $jsonConverter = new JsonConverter();
        $algorithmManager = new AlgorithmManager([
          new PS256()
        ]);

        $jwsBuilder = new JWSBuilder($algorithmManager);

        $now = time();

        $claims = [
          'aud' => 'https://iam.api.cloud.yandex.net/iam/v1/tokens',
          'iss' => $service_account_id,
          'iat' => $now,
          'exp' => $now + 360
        ];

        $header = [
          'alg' => 'PS256',
          'typ' => 'JWT',
          'kid' => $key_id
        ];

        $key = JWKFactory::createFromKeyFile(base_path('storage/private.pem'));

        $payload = $jsonConverter->encode($claims);

        // Формирование подписи.
        $jws = $jwsBuilder
          ->create()
          ->withPayload($payload)
          ->addSignature($key, $header)
          ->build();

        $serializer = new CompactSerializer($jsonConverter);

        // Формирование JWT.
        $token = $serializer->serialize($jws);

        return $token;
    }

    public static function updateIAmToken()
    {
      $ch = curl_init();

      $JWTTooken = self::updateJWTToken();

      curl_setopt($ch, CURLOPT_URL, 'https://iam.api.cloud.yandex.net/iam/v1/tokens');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"jwt\": \"$JWTTooken\"}");
      curl_setopt($ch, CURLOPT_POST, 1);

      $headers = array();
      $headers[] = 'Content-Type: application/json';
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

      $result = curl_exec($ch);
      if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
      }
      curl_close($ch);

      return $result;
    }

    public static function getIAmToken()
    {
        return json_decode(file_get_contents(base_path('storage/iamtoken.key')))->iamToken;
    }
}
