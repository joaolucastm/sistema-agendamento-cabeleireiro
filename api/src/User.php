<?php

class User
{
	private DB $db;
	private string $table = 'users';
	private string $emailKey = 'email';
	private string $idKey = 'id';
	private string $nameKey = 'name';
	private string $passwordKey = 'password';

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

	public function create(string $email, string $password): array
	{
		$data = [
			$this->emailKey => $email,
			$this->passwordKey => password_hash($password, PASSWORD_DEFAULT)
		];

		$result = $this->db->create($this->table, $data);
	
		return $result;
	}

    public function login(string $email, string $password): bool
    {
        $read = $this->db->readLogin($this->table, $email, $this->emailKey, $this->idKey, $this->nameKey, $this->passwordKey);
        $hash = $read['hash']; 
        
        if ($read['success'] && password_verify($password, $hash))
        {
            session_regenerate_id(true);
            $_SESSION['userEmail'] = $email;
            $_SESSION['userId'] = $read['id'];
            $_SESSION['userName'] = $read['name'];
            return true;
        }

        return false;
    }

	public function logout(): bool
	{
		$params = session_get_cookie_params();
		
		setcookie(
			session_name(), '', time() - 86400,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
		
		return session_unset() && session_destroy();
	}
}
