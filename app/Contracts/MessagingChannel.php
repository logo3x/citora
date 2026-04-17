<?php

namespace App\Contracts;

interface MessagingChannel
{
    public function send(string $to, string $message): bool;
}
