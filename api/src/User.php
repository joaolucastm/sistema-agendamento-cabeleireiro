<?php

class User
{
	public function __construct(
		private DB $db,
		private string $table = 'users', 
	    private string $emailKey = 'email', 
		private string $passwordKey = 'password'
	) {}

	public function create(string $email, string $password): bool
	{
		$data = [
			$this->emailKey => $email,
			$this=>passwordKey => password_hash($password, PASSWORD_DEFAULT)
		];

		$result = $this->db->create($this->table, $data);

		return $result['success'];
	}
	
	public function login(string $email, string $password): bool
    {
        $read = $this->db->readLogin($this->table, $email, $this->emailKey, $this->passwordKey);
        $hash = $read['data']; 
        
        if ($read['success'] && $hash && password_verify($password, $hash))
        {
            session_regenerate_id(true);
            $_SESSION['loggedUser'] = $email;
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
