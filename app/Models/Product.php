<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Product
{
    public static function getAll(): array
    {
        $db = Database::connection();

        $sql = "
            SELECT p.*, m.path AS thumbnail_url 
            FROM products p
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = p.id 
                  AND mr.entity_type = 'product'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            ORDER BY p.id DESC
        ";

        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::connection();

        $sql = "
            SELECT p.*, m.path AS thumbnail_url 
            FROM products p
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = p.id 
                  AND mr.entity_type = 'product'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            WHERE p.slug = :slug 
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
        WHERE mr.entity_type = 'product' 
          AND mr.entity_id = :id 
          AND mr.usage_type = 'gallery'
        ORDER BY mr.sort_order ASC
    ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $destinationId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
    }
}
