<?php

class DB
{
   	private PDO $connection;

	public function __construct($credentials)
	{
	    try
        {
        	$this->connection = new PDO(
        	    "mysql:host={$credentials['hostname']};dbname={$credentials['database']};charset=utf8mb4", 
        	    $credentials['username'], 
        	    $credentials['password'], 
        	    [
        	        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        	        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        	        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '-03:00'" 
        	    ]
        	);
        }
        catch (Throwable $e)
        {
        	$this->logError($e);
        	throw new Exception("Error connecting to database!");
        }
	}

	private function logError(Throwable $e): void
	{
	    error_log(
	        date('Y-m-d H:i:s') . ' | ' . $e . PHP_EOL,
	        3,
	        '../../src/errors.log'
	    );
	}

	public function create(string $table, array $data): array
	{
		try
		{	
			$columns = implode(', ', array_keys($data));
			$placeholders = ':' . implode(', :', array_keys($data));

			$sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute($data);
						
			return ['success' => true, 'affectedRows' => $stmt->rowCount()];
		}
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}

	public function read(string $columns = '*', string $table, ?string $parameters = null): array
	{
		try
		{
			$sql = "SELECT $columns FROM $table $parameters";
			$stmt = $this->connection->prepare($sql);
			$stmt->execute();
			$data = $stmt->fetchAll();

			return ['success' => true, 'data' => $data];
		}
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}

	public function readLogin(string $table, string $email, string $emailKey, string $passwordKey): array
	{
		try
		{
			$sql = "SELECT $passwordKey FROM $table WHERE $emailKey = :$emailKey ";
			$stmt = $this->connection->prepare($sql);
			$stmt->execute([":$emailKey" => $email]);
			$hash = $stmt->fetchColumn();

			return ['success' => true, 'data' => $hash];
		}
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}

	public function search(string $selectColumns = '*', string $table, array $searchColumns, string $keyWord, ?string $joinParameters = null, ?string $whereParameters = null): array
	{
		try
		{
			$likeClauses = [];

			foreach ($searchColumns as $column)
			{
				$likeClauses[] = "$column LIKE :keyWord";
			}

			$whereClause = implode(' OR ', $likeClauses);

			$sql = "SELECT $selectColumns FROM $table $joinParameters WHERE ($whereClause) $whereParameters";
			
			$stmt = $this->connection->prepare($sql);
			$stmt->bindValue(':keyWord', "%$keyWord%", PDO::PARAM_STR);
			$stmt->execute();

			$data = $stmt->fetchAll();

			return ['success' => true, 'data' => $data];
		}
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}
	
	public function count(string $table, ?string $parameters = null): array
	{
		try
		{
			$sql = "SELECT COUNT(*) FROM $table $parameters";

			$data = $this->connection->query($sql)->fetchColumn();

			return ['success' => true, 'data' => $data];
		}
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}
	
	public function sum(string $table, string $column, ?string $parameters = null): array
	{
		try
		{
			$sql = "SELECT SUM($column) FROM $table $parameters";

			$data = $this->connection->query($sql)->fetchColumn();
			$data === null ? $data = 0 : null;
			
			return ['success' => true, 'data' => $data];
		} 	
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}

	public function update(string $table, array $data, array $key): array
	{
		try
		{
			$keyName = array_key_first($key);
			$keyValue = $key[$keyName];
			$updateAssignments = [];

			$data[$keyName] = $keyValue;

			foreach ($data as $column => $value)
			{
				$updateAssignments[] = "$column = :$column";
			}

			$updates = implode(', ', $updateAssignments);

			$sql = "UPDATE $table SET $updates WHERE $keyName = :$keyName";

			$stmt = $this->connection->prepare($sql);
			$stmt->execute($data);

			return ['success' => true, 'affectedRows' => $stmt->rowCount()];
		}
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}

	public function delete(string $table, array $key, bool $hard, string $softDeleteColumn = 'deleted_at'): array
	{
		try
		{
			$keyName = array_key_first($key);
			$sql = '';

			if ($hard)
			{
				$sql = "DELETE FROM $table WHERE $keyName = :$keyName";
			}
			else
			{
				$sql = "UPDATE $table SET $softDeleteColumn = NOW() WHERE $keyName = :$keyName";
			}

			$stmt = $this->connection->prepare($sql);
			$stmt->execute($key);

			return ['success' => true, 'affectedRows' => $stmt->rowCount()];
		}
		catch (Throwable $e)
		{
			$this->logError($e);
			return ['success' => false, 'errorCode' => $e->getCode()];
		}
	}
}
