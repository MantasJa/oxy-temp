<?php

use Symfony\Component\Dotenv\Dotenv;

// Composer autoload
require dirname(__DIR__) . '/vendor/autoload.php';

// Load .env.test if it exists
if (file_exists(dirname(__DIR__) . '/.env.test')) {
    (new Dotenv())->usePutenv()->loadEnv(dirname(__DIR__) . '/.env.test');
}
