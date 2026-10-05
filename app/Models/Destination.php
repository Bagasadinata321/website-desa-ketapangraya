<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Destination
{
    public static function getAll(): array
    {
        $db = Database::connection();

        // JOIN menggunakan entity_id dan entity_type sesuai skema DB
        $sql = "
            SELECT d.*, m.path AS thumbnail_url 
            FROM destinations d
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = d.id 
                  AND mr.entity_type = 'destination'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            ORDER BY d.id DESC
        ";

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::connection();

        $sql = "
            SELECT d.*, m.path AS thumbnail_url 
            FROM destinations d
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = d.id 
                  AND mr.entity_type = 'destination'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            WHERE d.slug = :slug 
            LIMIT 1
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    public static function getGallery(int $destinationId): array
    {
        $db = Database::connection();
        $sql = "
        SELECT m.path 
        FROM media_relations mr
        JOIN media m ON m.id = mr.media_id
        WHERE mr.entity_type = 'destination' 
          AND mr.entity_id = :id 
          AND mr.usage_type = 'gallery'
        ORDER BY mr.sort_order ASC
    ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $destinationId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }
}
