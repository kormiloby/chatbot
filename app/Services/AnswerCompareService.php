<?php
declare(strict_types=1);

namespace App\Services;

class AnswerCompareService
{
    private $minWordLen = 3;

    private $subtokenLenght = 3;

    private $thresholdSentence = 0.5;

    private $thresholdWord = 0.45;

    /**
    *
    */
    public function calculateFuzzyEqualValue(string $first, string $second)
    {
        if (is_null($first) && is_null($second)) {
            return 1.0;
        }

        if (is_null($first) || is_null($second)) {
            return 0.0;
        }

        $normalizedFirst = $this->normalizeSentence($first);
        $normalizedSecond = $this->normalizeSentence($second);

        $tokenFirst = $this->getToken($normalizedFirst);
        $tokenSecond = $this->getToken($normalizedSecond);

        $fuzzyEqualsTokens = $this->getFuzzyEqualsTokens($tokenFirst, $tokenSecond);

        $equalCount = count($fuzzyEqualsTokens);
        $firstCount = count($tokenFirst);
        $secondCount = count($tokenSecond);

        $resultValue = (1.0 * $equalCount) / ($firstCount + $secondCount - $equalCount);

        return $this->thresholdSentence < $resultValue;
    }

    /**
    *
    */
    private function getFuzzyEqualsTokens(array $tokensFirst, array $tokensSecond): array
    {
        $equalsTokens = [];
        $usedTokens = [];

        for ($i = 0; $i < count($tokensFirst); ++$i) {
            for ($j = 0; $j < count($tokensSecond); ++$j) {
                if(!isset($usedTokens[$j])) {
                    if ($this->isTokenFuzzyEqual($tokensFirst[$i], $tokensSecond[$j])) {
                        $equalsTokens[] = $tokensFirst[$i];
                        $usedTokens[$j] = true;
                        break;
                    }
                }
            }
        }

        return $equalsTokens;
    }

    /**
    *
    */
    private function isTokenFuzzyEqual(string $firstToken, string $secondToken): bool
    {
        $equalSubtokenCount = 0;
        $usedTokens = [];
        for ($i = 0; $i <= strlen($firstToken) - $this->subtokenLenght + 1; $i++) {
            $subtokenFirst = substr($firstToken, $i, $this->subtokenLenght);
            for ($j = 0; $j <= strlen($secondToken) - $this->subtokenLenght; $j++) {
                if (!isset($usedTokens[$j])) {
                    $subtokenSecond = substr($secondToken, $j, $this->subtokenLenght);
                    if (!strcmp($subtokenFirst, $subtokenSecond)) {
                        $equalSubtokenCount++;
                        $usedTokens[$j] = true;
                        break;
                    }
                }
            }
        }

        $subtokenFirstCount = strlen($firstToken) - $this->subtokenLenght + 1;
        $subtokenSecondCount = strlen($secondToken) - $this->subtokenLenght + 1;

        $tanimoto = (1.0 * $equalSubtokenCount) / ($subtokenFirstCount + $subtokenSecondCount - $equalSubtokenCount);

        return $this->thresholdWord <= $tanimoto;
    }

    /**
    *
    */
    private function getToken(String $sentence): array
    {
        $tokens = [];
        $words = explode(" ", $sentence);
        foreach ($words as $word) {
            if (strlen($word) >= $this->minWordLen) {
                $tokens[] = $word;
            }
        }

        return $tokens;
    }

    /**
    *
    */
    function normalizeSentence(String $sentense): string
    {
        $resultContainer = "";
        $lowerSentence = strtolower($sentense);
        for ($i = 0; $i < strlen($lowerSentence); ++$i) {
            if ($this->isNormalChar($lowerSentence[$i])) {
              $resultContainer = $resultContainer . $lowerSentence[$i];
            }
        }

        return $resultContainer;
    }

    /**
    *
    */
    private function isNormalChar($c): bool
    {
        return ctype_digit($c) || preg_match("/[а-я]/i", $c) || $c === " " ? true : false;
    }
}
