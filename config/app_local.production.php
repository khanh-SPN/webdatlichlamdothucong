<?php
/**
 * PRODUCTION CONFIGURATION FILE
 * 
 * Copy this file to app_local.php on your production server
 * and update the settings as needed.
 */

use Cake\Mailer\Transport\SmtpTransport;
use function Cake\Core\env;

return [
    // Production: disable debug
    'debug' => false,

    'Security' => [
        'salt' => env('SECURITY_SALT', 'your-unique-salt-here-change-this'),
    ],

    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            'port' => '3306',
            'username' => 'your_db_username',
            'password' => 'your_db_password',
            'database' => 'fit3047',
            'encoding' => 'utf8mb4',
            'url' => env('DATABASE_URL', null),
        ],
        'test' => [
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'database' => 'fit3047_test',
            'encoding' => 'utf8mb4',
        ],
    ],

    /*
     * Gmail SMTP Configuration - Candlecraft App
     * 
     * App Name: candlecraft
     * App Password: pfhk smuz fcfi ruie (no spaces: pfhksmuzfcfiruie)
     * Email: candlecraft.fit3047@gmail.com
     * 
     * To generate app password:
     * 1. Go to Google Account settings
     * 2. Security > 2-Step Verification (must be enabled)
     * 3. App passwords > Select app > Generate
     */
    'EmailTransport' => [
        'default' => [
            'className' => SmtpTransport::class,
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'timeout' => 30,
            'username' => 'candlecraft.fit3047@gmail.com',
            'password' => 'pfhksmuzfcfiruie',
            'tls' => true,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],

    'Email' => [
        'default' => [
            'transport' => 'default',
            'from' => ['candlecraft.fit3047@gmail.com' => 'Candlecraft'],
        ],
    ],

    // Cloudflare Turnstile - Update with production keys
    'Captcha' => [
        'turnstile' => [
            'siteKey' => env('TURNSTILE_SITE_KEY', 'your-production-site-key'),
            'secretKey' => env('TURNSTILE_SECRET_KEY', 'your-production-secret-key'),
        ],
    ],

    // Stripe - Update with production keys
    'Stripe' => [
        'secretKey' => env('STRIPE_SECRET_KEY', 'sk_live_your_production_key'),
    ],

    // Production base URL - IMPORTANT for security
    'App' => [
        'fullBaseUrl' => env('APP_FULL_BASE_URL', 'https://your-domain.com'),
    ],
];
