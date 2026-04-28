<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

#[Signature('push:generate-vapid')]
#[Description('Generate a fresh VAPID public/private key pair for Web Push notifications.')]
class GenerateVapidKeys extends Command
{
    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $this->info('VAPID keys generated successfully.');
        $this->line('');
        $this->line('Add these to your .env file:');
        $this->line('');
        $this->line('VAPID_PUBLIC_KEY='.$keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY='.$keys['privateKey']);
        $this->line('VAPID_SUBJECT=mailto:contacto@citora.com.co');
        $this->line('');
        $this->warn('Keep the PRIVATE key secret and never expose it in client code.');

        return Command::SUCCESS;
    }
}
