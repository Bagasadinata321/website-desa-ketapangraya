<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class PageSection
{
    public static function getSectionsByPage(string $pageSlug): array
    {
        $db = Database::connection();

        $sql = "
            SELECT s.*, 
                   m.path AS image_path
            FROM sections s
            JOIN pages p ON p.id = s.page_id
            LEFT JOIN media_relations mr 
                   ON mr.entity_id = s.id 
                  AND mr.entity_type = 'section'
            LEFT JOIN media m 
                   ON m.id = mr.media_id
            WHERE p.slug = :slug
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute(['slug' => $pageSlug]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $sections = [];
        foreach ($rows as $row) {
            $key = $row['section_key'];

            // Decode meta JSON (seperti data statistik)
            $meta = [];
            if (!empty($row['meta'])) {
                $meta = json_decode($row['meta'], true) ?: [];
            }

            $sections[$key] = array_merge($row, [
                'meta'  => $meta,
                'image' => $row['image_path'] ?? null
            ]);
        }

        return $sections;
    }
}
