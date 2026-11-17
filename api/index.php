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

ini_set('date.timezone', 'America/Sao_Paulo');
ini_set('display_errors', 1);
ini_set('session.gc_maxlifetime', 3600);

session_start([
    'cookie_lifetime'  => 3600,
    'cookie_path'      => '/',
    'cookie_domain'    => '',
    'cookie_secure'    => true,
    'cookie_httponly'  => true,
    'cookie_samesite'  => 'Strict',
    'use_only_cookies' => true,
    'use_strict_mode'  => true,
]);

require_once 'src/DB.php';
require_once 'src/User.php';

function respond(int $status, array $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

// if ($_SERVER['REQUEST_METHOD'] !== 'POST')
// {
//     respond(405, ['success' => false, 'message' => 'Method not allowed.']);
// }

$dbCredentials = [
    'hostname' => 'localhost',
    'username' => 'http',
    'password' => '',
    'database' => 'scheduling',
];

$db = new DB($dbCredentials);
$user = new User($db);

$action = $_POST['action'] ?? '';
// $publicActions = ['create', 'login'];
// 
// if (!in_array($action, $publicActions, true) && empty($_SESSION['loggedUser']))
// {
//     respond(401, ['success' => false, 'message' => 'Unauthorized.']);
// }

switch ($action)
{
    case 'create':

        if (empty($_POST['email']) || empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['password']))
        {
            respond(400, ['success' => false, 'message' => 'Todos os campos são obrigatórios.']);
        }

        $email    = trim($_POST['email']);
        $password = $_POST['password'];
        $name     = trim($_POST['name']);
        $phone    = trim($_POST['phone']);

        $result = $user->create($email, $password);

        if (!$result['success'])
        {
            respond(400, $result);
        }

        $updated = $db->update('users', ['name' => $name, 'phone' => $phone], ['email' => $email]);

        if (!$updated['success'])
        {
            respond(500, ['success' => false, 'message' => 'Usuário criado, mas não foi possível atualizar o perfil.']);
        }

        respond(201, $result);

    case 'login':

        if (empty($_POST['email']) || empty($_POST['password']))
        {
            respond(400, ['success' => false, 'message' => 'E-mail e senha são obrigatórios.']);
        }

        $email    = trim($_POST['email']);
        $password = $_POST['password'];

        $result = $user->login($email, $password);

        respond($result ? 200 : 401, ['success' => $result]);

    case 'logout':

        $result = $user->logout();

        respond(200, $result);

    case 'listUsers':

        $result = $db->read('*', 'users');

        respond(200, $result);

    default:

        respond(400, ['success' => false, 'message' => 'Ação desconhecida.']);
}

