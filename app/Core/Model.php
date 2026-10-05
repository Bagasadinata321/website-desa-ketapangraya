<?php

namespace App\Core;

use App\Core\Logger;
use App\Core\Database;
use PDO;

class Model
{
    protected $table;
    protected $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }
    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function all()
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1"
        );

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBy($column, $value, $orderBy = null, $direction = 'ASC')
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :value";

        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy} {$direction}";
        }

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'value' => $value
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findOne($column, $value)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE {$column} = :value"
        );

        $stmt->execute([
            'value' => $value
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $columns = implode(',', array_keys($data));
        $values  = ':' . implode(', :', array_keys($data));

        $stmt = $this->db->prepare(
            "INSERT INTO {$this->table} ($columns) VALUES ($values)"
        );

        try {

            // jalankan query
            if (!$stmt->execute($data)) {

                // 🔴 ambil info error dari PDO
                $errorInfo = $stmt->errorInfo();

                Logger::error(
                    "Insert gagal | table: {$this->table} | error: " . ($errorInfo[2] ?? 'unknown')
                );

                return false;
            }

            // 🔥 pengecualian: tabel tertentu return insert ID
            if ($this->table === 'weddings') {
                return $this->db->lastInsertId();
            }

            return true;
        } catch (\Throwable $e) {

            // 🔴 log error detail
            Logger::error(
                "Exception saat insert | table: {$this->table} | message: " . $e->getMessage()
            );

            return false;
        }
    }

    public function update($id, $data)
    {
        $fields = '';

        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }

        $fields = rtrim($fields, ', ');

        $data['id'] = $id;

        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET $fields WHERE id = :id"
        );

        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
    public function getInput($column)
    {
        $stmt = $this->db->prepare("SELECT DISTINCT $column FROM {$this->table} ORDER BY $column ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    protected function getTableColumns()
    {
        $stmt = $this->db->prepare("DESCRIBE {$this->table}");
        $stmt->execute();

        return array_column(
            $stmt->fetchAll(PDO::FETCH_ASSOC),
            'Field'
        );
    }
    protected function filterColumns(array $data)
    {
        $columns = $this->getTableColumns();

        return array_intersect_key(
            $data,
            array_flip($columns)
        );
    }
    public function createFiltered($data)
    {
        $data = $this->filterColumns($data);

        return $this->create($data);
    }
    public function updateFiltered($id, $data)
    {
        $data = $this->filterColumns($data);
        return $this->update($id, $data);
    }
    public function count(string $column, $value): int
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) as total 
        FROM {$this->table} 
        WHERE {$column} = ?
    ");
        $stmt->execute([$value]);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function sum(string $column, string $whereColumn, $whereValue): int
    {
        $stmt = $this->db->prepare("
        SELECT SUM({$column}) as total 
        FROM {$this->table} 
        WHERE {$whereColumn} = ?
    ");
        $stmt->execute([$whereValue]);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
