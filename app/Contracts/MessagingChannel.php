<?php

namespace App\Contracts;

interface MessagingChannel
{
    public function send(string $to, string $message): bool;

    /**
     * Send a pre-approved template message (WhatsApp) or fall back to plain text
     * for channels that don't support templates (SMS providers).
     *
     * @param  array<int|string, string>  $variables  Positional variables for the template (index 1..n).
     */
    public function sendTemplate(string $to, string $templateKey, array $variables, string $fallbackText): bool;
}
