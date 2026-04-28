<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'channel' => env('TWILIO_CHANNEL', 'sms'),
        'sms_from' => env('TWILIO_SMS_FROM'),
        'whatsapp_from' => env('TWILIO_WHATSAPP_FROM'),

        // WhatsApp pre-approved Content SIDs (start with HX...) per template key.
        // Used by WhatsAppService::sendTemplate() when TWILIO_CHANNEL=whatsapp.
        'templates' => [
            'appointment.confirmed.customer' => env('TWILIO_TPL_APPOINTMENT_CONFIRMED_CUSTOMER'),
            'appointment.new.internal' => env('TWILIO_TPL_APPOINTMENT_NEW_INTERNAL'),
            'appointment.reminder.customer' => env('TWILIO_TPL_APPOINTMENT_REMINDER_CUSTOMER'),
            'appointment.reminder.internal' => env('TWILIO_TPL_APPOINTMENT_REMINDER_INTERNAL'),
            'appointment.cancelled' => env('TWILIO_TPL_APPOINTMENT_CANCELLED'),
            'appointment.rescheduled' => env('TWILIO_TPL_APPOINTMENT_RESCHEDULED'),
            'employee.welcome' => env('TWILIO_TPL_EMPLOYEE_WELCOME'),
        ],
    ],

    'webpush' => [
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
        'subject' => env('VAPID_SUBJECT', 'mailto:contacto@citora.com.co'),
    ],

    'hablame' => [
        'account' => env('HABLAME_ACCOUNT'),
        'api_key' => env('HABLAME_API_KEY'),
        'endpoint' => env('HABLAME_ENDPOINT', 'https://www.hablame.co/api/sms/v5/send/priority'),
    ],

    'wompi' => [
        'public_key' => env('WOMPI_PUBLIC_KEY'),
        'private_key' => env('WOMPI_PRIVATE_KEY'),
        'events_secret' => env('WOMPI_EVENTS_SECRET'),
        'integrity_secret' => env('WOMPI_INTEGRITY_SECRET'),
        'plans' => [
            'monthly' => ['price' => (int) env('WOMPI_UNLOCK_PRICE_MONTHLY', 34900), 'days' => 30],
            'semester' => ['price' => (int) env('WOMPI_UNLOCK_PRICE_SEMESTER', 179400), 'days' => 180],
        ],
        'origin' => env('WOMPI_ORIGIN', 'citora'),
        'currency' => 'COP',
        'redirect_base' => env('WOMPI_REDIRECT_BASE'),
        'base_url' => env('WOMPI_ENV', 'test') === 'production'
            ? 'https://production.wompi.co'
            : 'https://sandbox.wompi.co',
    ],

    'deploy' => [
        'secret' => env('DEPLOY_SECRET'),
    ],

    'cron' => [
        'secret' => env('CRON_SECRET'),
    ],

    'gtm' => [
        'container_id' => env('GTM_CONTAINER_ID'),
    ],

    'ga4' => [
        'measurement_id' => env('GA4_MEASUREMENT_ID'),
    ],

];
