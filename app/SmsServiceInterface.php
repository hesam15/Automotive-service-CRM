<?php

namespace App;

interface SmsServiceInterface
{
    public function send(string $to, string $message): bool;
    public function sendPattern(string $to, string $patternCode, array $inputData): bool;
}
