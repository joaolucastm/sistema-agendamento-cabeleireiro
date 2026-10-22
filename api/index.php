<?php 

$accessAllowed = 1;

if (!$accessAllowed)
{
    $sessionPath = ini_get('session.save_path') . '/sess_*';
    $files = glob($sessionPath);
    
    foreach ($files as $file)
    {
        unlink($file);
    }

    http_response_code(403);
    exit;
}

ini_set('display_errors', 1);
ini_set('session.gc_maxlifetime', 3600);
// ini_set('date.timezone', 'America/Sao_Paulo');

session_start([
    'cookie_lifetime' => 3600,
    'cookie_path'     => '/',
    'cookie_domain'   => '',
    'cookie_secure'   => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Strict',
    'use_only_cookies' => true,
    'use_strict_mode' => true,
]);

$dbCredentials = [
	'hostname' => 'localhost',
	'username' => 'http',
	'password' => '',
	'database' => 'api',
];

require_once 'src/DB.php';
require_once 'src/User.php';

header('Content-Type: application/json; charset=utf-8');

$data = [
    'message' => 'Data retrieved successfully',
    'user' => [
        'id' => 123,
        'name' => 'Alice 😁'
    ]
];

echo json_encode($data, JSON_UNESCAPED_UNICODE);

exit;
