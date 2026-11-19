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
        return $this->db->read('*', $this->table, 'WHERE deleted_at IS NULL ORDER BY created_at ASC');
    }

    public function readClient(): array
    {
        return $this->db->read('*', $this->table, "WHERE deleted_at IS NULL AND client_id = {$_SESSION['userId']} ORDER BY created_at DESC");
    }

    public function read(int $id): array
    {
        return $this->db->read('*', $this->table, "WHERE id = $id AND deleted_at IS NULL");
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
