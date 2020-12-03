<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Services\SessionService;
use Dotenv\Dotenv;

$session = new SessionService;
if (isset($_REQUEST['session_id'])) {
    $session->startSession($_REQUEST['session_id']);
} else {
    $session->startSession();
}

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->required(['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD'])->notEmpty();
$dotenv->load();
