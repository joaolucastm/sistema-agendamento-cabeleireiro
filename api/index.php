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

ini_set('display_errors', 0);
ini_set('date.timezone', 'America/Sao_Paulo');

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
require_once 'src/dbCredentials.php';
require_once 'src/User.php';
require_once 'src/Appointment.php';
require_once 'src/Redirect.php';
require_once 'src/Notification.php';

try
{
	$db = new DB($dbCredentials);
}
catch (Exception $e)
{
	respond(500, ['success' => false, 'message' => $e->getMessage()]);
}

$user = new User($db);
$appointment = new Appointment($db);

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
// if (!in_array($action, $publicActions, true) && empty($_SESSION['userEmail']))
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
            respond(400, ['success' => false, 'message' => "Erro ao criar usuário: {$result['errorCode']}."]);
        }

        $updated = $db->update('users', ['name' => $name, 'phone' => $phone], ['email' => $email]);

        if ($updated['success'])
        {
        	respond(201, ['success' => true, 'message' => 'Usuário criado com sucesso!']);
        }
		else
		{
            respond(500, ['success' => false, 'message' => 'Usuário criado, mas não foi possível atualizar o perfil.']);
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
        	respond(200, ['success' => true, 'message' => "Autenticado como {$_SESSION['userEmail']}."]);
		}
		else
		{
        	respond($result ? 200 : 401, ['success' => false, 'message' => 'Credenciais inválidas.']);
		}
		
	case 'createAppointment':

        if (empty($_POST['schedule']))
        {
            respond(400, ['success' => false, 'message' => 'Um horário é obrigatório.']);
        }

		$read = $appointment->checkAvailable($_POST['schedule']);

		if ($read['success'] && !empty($read['data']))
		{
        	respond(400, ['success' => false, 'message' => 'Horário já agendado!']);
		}
		
        $result = $appointment->create([
        	'client_id' => $_SESSION['userId'],
        	'schedule' => $_POST['schedule'],
       	]);

		if ($result['success'])
		{
        	respond(201, ['success' => true, 'message' => 'Agendado com sucesso!']);
		}
		else
		{
        	respond(400, ['success' => false, 'message' => "Erro ao agendar: {$result['errorCode']}."]);
		}

    case 'listClientAppointments':

        $result = $appointment->readClient();

		if ($result['success'])
		{
			$result['message'] = 'Agendamentos listados.';
        	respond(200, $result);
		}
		else
		{
        	respond(400, ['success' => false, 'message' => "Erro ao listar agendamentos: {$result['errorCode']}."]);
		}

	case 'listAppointments':

		$result = $appointment->readAll();

		if ($result['success'])
		{
			$result['message'] = 'Agendamentos listados.';
	       	respond(200, $result);
		}
		else
		{
	       	respond(400, ['success' => false, 'message' => "Erro ao listar agendamentos: {$result['errorCode']}."]);
		}

    case 'confirmAppointment':

        $result = $appointment->update($_POST['id'], ['status' => 'Confirmado']);

		if ($result['success'])
		{
			if ($result['affectedRows'] > 0) respond(200, ['success' => true, 'message' => 'Confirmado com sucesso!']);
			else respond(400, ['success' => false, 'message' => 'Já está confirmado.']);
		}
		else
		{
        	respond(400, ['success' => false, 'message' => "Erro ao confirmar: {$result['errorCode']}."]);
		}
		
    case 'cancelAppointment':

        $result = $appointment->update($_POST['id'], ['status' => 'Cancelado']);

		if ($result['success'])
		{
			if ($result['affectedRows'] > 0) respond(200, ['success' => true, 'message' => 'Cancelado com sucesso!']);
			else respond(400, ['success' => false, 'message' => 'Já está cancelado.']);
		}
		else
		{
        	respond(400, ['success' => false, 'message' => "Erro ao cancelar: {$result['errorCode']}."]);
		}

    default:

        respond(400, ['success' => false, 'message' => 'Ação desconhecida.']);
}

