<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Official
{
    public static function getAll(): array
    {
        $db = Database::connection();

        $sql = "
            SELECT o.*, m.path AS thumbnail_url 
            FROM officials o
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = o.id 
                  AND mr.entity_type = 'official'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            WHERE o.is_active = 1
            ORDER BY o.sort_order ASC, o.id ASC
        ";

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
