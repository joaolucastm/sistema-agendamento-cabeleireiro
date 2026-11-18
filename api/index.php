<?php

// blockAcess();

function blockAcess($clearSessions = false)
{
	if ($clearSessions)
	{
	    $sessionPath = ini_get('session.save_path') . '/sess_*';
	    $files = glob($sessionPath);

	    foreach ($files as $file)
	    {
	        unlink($file);
	    }
	}

    http_response_code(403);
    exit;
}

function respond(int $status, array $payload): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

ini_set('date.timezone', 'America/Sao_Paulo');
ini_set('display_errors', 1);

ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_lifetime', '3600');
ini_set('session.cookie_path', '/');
ini_set('session.cookie_secure', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');

session_start();

require_once 'src/DB.php';
require_once 'src/User.php';
require_once 'src/Redirect.php';
require_once 'src/Notification.php';

$dbCredentials = [
    'hostname' => 'localhost',
    'username' => 'http',
    'password' => '',
    'database' => 'scheduling',
];

$db = new DB($dbCredentials);
$user = new User($db);

if (isset($_GET['logout']))
{
	$user->logout();
	session_start();
	Notification::set('Desconectado.', 'success');
	Redirect::to('../index.php');
}

$action = $_POST['action'] ?? '';

// $publicActions = ['create', 'login'];
// 
// if (!in_array($action, $publicActions, true) && empty($_SESSION['loggedUser']))
// {
//     respond(401, ['success' => false, 'message' => 'Unauthorized.']);
// }

switch ($action)
{
    case 'signUp':

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
            respond(400, ['success' => false, 'message' => "Erro ao criar usuário: {$result['errorCode']}"]);
        }

        $updated = $db->update('users', ['name' => $name, 'phone' => $phone], ['email' => $email]);

        if (!$updated['success'])
        {
            respond(500, ['success' => false, 'message' => 'Usuário criado, mas não foi possível atualizar o perfil.']);
        }
		else
		{
        	respond(201, ['success' => true, 'message' => 'Usuário criado com sucesso!']);
		}

    case 'login':

        if (empty($_POST['email']) || empty($_POST['password']))
        {
            respond(400, ['success' => false, 'message' => 'E-mail e senha são obrigatórios.']);
        }

        $email    = trim($_POST['email']);
        $password = $_POST['password'];

        $result = $user->login($email, $password);

		if ($result)
		{
        	respond(200, ['success' => true, 'message' => "Autenticado como {$_SESSION['loggedUser']}"]);
		}
		else
		{
        	respond($result ? 200 : 401, ['success' => false, 'message' => 'Credenciais inválidas.']);
		}

    case 'listUsers':

        $result = $db->read('*', 'users');

        respond(200, $result);

    default:

        respond(400, ['success' => false, 'message' => 'Ação desconhecida.']);
}

