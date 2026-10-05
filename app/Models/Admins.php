<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class Admins extends Model
{
    protected $table = 'admins';

    public function __construct()
    {
        // Menggunakan method connection() dari kelas App\Core\Database Anda
        $this->db = Database::connection();
    }

    /**
     * Simpan admin baru dengan status 'pending'
     */
    public function createAdmin(array $data): bool
    {
        $sql = "INSERT INTO users (nama, email, password, role, status) 
                VALUES (:nama, :email, :password, 'admin', 'pending')";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nama'     => $data['nama'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT)
        ]);
    }

    /**
     * Cari user berdasarkan email
     */
    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(); // Mengembalikan array assoc karena ATTR_DEFAULT_FETCH_MODE sudah FETCH_ASSOC
    }

    /**
     * Update status user (untuk Super Admin saat Approve/Reject)
     */
    public function updateStatus(int $userId, string $status): bool
    {
        $sql = "UPDATE users SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':status' => $status,
            ':id'     => $userId
        ]);
    }
}
