<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Program
{
    public static function getAll(): array
    {
        $db = Database::connection();

        $sql = "
            SELECT p.*, 
                   GROUP_CONCAT(m.path ORDER BY mr.id ASC) AS images_str
            FROM programs p
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = p.id 
                  AND mr.entity_type = 'program'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            WHERE p.is_active = 1
            GROUP BY p.id
            ORDER BY p.sort_order ASC, p.id ASC
        ";

        $stmt = $db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Format string images_str menjadi array (maksimal 3 gambar)
        foreach ($results as &$item) {
            if (!empty($item['images_str'])) {
                $imgs = explode(',', $item['images_str']);
                $item['images'] = array_slice($imgs, 0, 3);
            } else {
                $item['images'] = [];
            }
        }

        return $results;
    }
}
