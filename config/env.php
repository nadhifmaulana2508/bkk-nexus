<?php
/**
 * Pengaturan Base URL dinamis (Local/aaPanel)
 */

$isLocal = (
    isset($_SERVER['SERVER_NAME']) && 
    ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1')
);

if ($isLocal) {
    define('BASE_URL', 'http://localhost/bkk-nexus/');
} else {
    define('BASE_URL', 'https://yourdomain.com/');
}

define('APP_NAME', 'ePipeline');
define('APP_TAGLINE', 'AO Credit Operating System');
define('APP_VERSION', '1.0.0');
