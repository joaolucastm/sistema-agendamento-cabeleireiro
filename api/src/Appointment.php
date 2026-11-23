<?php

class Appointment
{
    private DB $db;
    private string $table = 'appointments';

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function create(array $data): array
    {
        return $this->db->create($this->table, $data);
    }

    public function readAll(): array
    {
		$result = $this->db->read('appointments.*, users.name, users.phone', $this->table, 'JOIN users ON appointments.client_id = users.id WHERE appointments.deleted_at IS NULL ORDER BY created_at ASC');
		
    	foreach ($result['data'] as &$item)
    	{
    		$item['schedule'] = (new DateTime($item['schedule']))->format('d/m/y à\s H:i');
    	}
    	
        return $result;
    }

	public function readScheduleds(): array
    {
        return $this->db->read('*', $this->table, 'WHERE status != \'Cancelado\' AND deleted_at IS NULL ORDER BY created_at ASC');
    }
    
    public function checkAvailable($value): array
    {
        return $this->db->read('*', $this->table, "WHERE schedule = '$value' AND status != 'Cancelado' AND deleted_at IS NULL");
    }

    public function readClient(): array
    {
		$result = $this->db->read('*', $this->table, "WHERE deleted_at IS NULL AND client_id = {$_SESSION['userId']} ORDER BY created_at DESC");

		foreach ($result['data'] as &$item)
		{
			$item['schedule'] = (new DateTime($item['schedule']))->format('d/m/y à\s H:i');
		}
		
        return $result;
    }

    public function read($column, $value): array
    {
        return $this->db->read('*', $this->table, "WHERE $column = '$value' AND deleted_at IS NULL");
    }

    public function update(int $id, array $data): array
    {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete(int $id, bool $hard = false): array
    {
        return $this->db->delete($this->table, ['id' => $id], $hard);
    }
}
