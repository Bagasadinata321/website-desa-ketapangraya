<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Student
{
    public static function getAll(): array
    {
        $db = Database::connection();

        $sql = "
            SELECT s.*, m.path AS thumbnail_url 
            FROM students s
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = s.id 
                  AND mr.entity_type = 'student'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            WHERE s.is_active = 1
            ORDER BY s.sort_order ASC, s.id ASC
        ";

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
