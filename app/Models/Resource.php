<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Model generik khusus untuk AdminController::resourceList().
 *
 * Beda dari model lain di folder ini (Admins, dst) yang terikat ke satu
 * tabel lewat property $table: class ini sengaja menerima nama tabel
 * sebagai PARAMETER di tiap method, karena satu instance dipakai
 * bergantian untuk semua resource (destinations, products, highlights,
 * officials, partners, admins) sesuai $resourceMap di controller.
 *
 * Makanya class ini TIDAK extends App\Core\Model — konsep single-table
 * Model (find/create/update lewat $this->table) tidak cocok dipakai
 * dengan cara controller memanggilnya.
 */
class Resource
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * SELECT COUNT(*) FROM {$table}
     */
    public function count(string $table): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM {$table}");

        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * SELECT * FROM {$table} WHERE id = :id LIMIT 1
     * Dipakai halaman edit untuk ambil satu baris berdasarkan id.
     */
    public function find(string $table, int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$table} WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * SELECT * FROM {$table} ORDER BY {$orderBy} {$direction} LIMIT :perPage OFFSET :offset
     *
     * @param string $orderBy   nama kolom untuk ORDER BY. HARUS berasal dari
     *                          config internal (resourceMap), BUKAN dari input
     *                          user — nama kolom tidak bisa di-bind sbg
     *                          parameter PDO seperti value biasa.
     */
    public function paginate(
        string $table,
        string $orderBy,
        int $page,
        int $perPage,
        string $direction = 'ASC'
    ): array {
        $direction = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';
        $offset    = max(0, ($page - 1) * $perPage);

        $stmt = $this->db->prepare(
            "SELECT * FROM {$table} ORDER BY {$orderBy} {$direction} LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Isi 'thumbnail_url' pada tiap item lewat lookup ke media_relations + media.
     * Dipanggil terpisah dari paginate() karena gambar tidak disimpan
     * langsung sebagai kolom di tabel resource (arsitektur polymorphic
     * media + media_relations).
     *
     * @param array  $items      hasil paginate(), tiap row wajib punya 'id'
     * @param string $entityType nilai media_relations.entity_type milik resource ini
     * @param string $usageType  default 'cover', sesuai kolom media_relations.usage_type
     */
    public function attachThumbnails(array $items, string $entityType, string $usageType = 'cover'): array
    {
        if (empty($items)) {
            return $items;
        }

        $ids          = array_map(static fn($row) => (int) $row['id'], $items);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT mr.entity_id, m.path
            FROM media_relations mr
            JOIN media m ON m.id = mr.media_id
            WHERE mr.entity_type = ?
              AND mr.usage_type = ?
              AND mr.entity_id IN ({$placeholders})
            ORDER BY mr.sort_order ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_merge([$entityType, $usageType], $ids));

        // Kalau ada lebih dari satu baris 'cover' untuk entity yang sama,
        // ambil yang sort_order paling kecil saja.
        $thumbnails = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $thumbnails[$row['entity_id']] ??= $row['path'];
        }

        foreach ($items as &$item) {
            $item['thumbnail_url'] = $thumbnails[$item['id']] ?? null;
        }
        unset($item);

        return $items;
    }

    /**
     * Ambil satu URL cover untuk satu entity (dipakai form edit/create,
     * beda dari attachThumbnails() yang untuk banyak baris sekaligus di list).
     */
    public function getCoverUrl(string $entityType, int $entityId, string $usageType = 'cover'): ?string
    {
        $stmt = $this->db->prepare("
            SELECT m.path
            FROM media_relations mr
            JOIN media m ON m.id = mr.media_id
            WHERE mr.entity_type = :entity_type
              AND mr.entity_id = :entity_id
              AND mr.usage_type = :usage_type
            ORDER BY mr.sort_order ASC
            LIMIT 1
        ");
        $stmt->execute([
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'usage_type'  => $usageType,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['path'] ?? null;
    }

    /**
     * Ambil semua URL gallery untuk satu entity (usage_type = 'gallery'),
     * dipakai form-lengkap (destinasi/produk).
     */
    public function getGalleryUrls(string $entityType, int $entityId, string $usageType = 'gallery'): array
    {
        $stmt = $this->db->prepare("
            SELECT m.path
            FROM media_relations mr
            JOIN media m ON m.id = mr.media_id
            WHERE mr.entity_type = :entity_type
              AND mr.entity_id = :entity_id
              AND mr.usage_type = :usage_type
            ORDER BY mr.sort_order ASC
        ");
        $stmt->execute([
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'usage_type'  => $usageType,
        ]);

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'path');
    }

    /**
     * DELETE FROM {$table} WHERE id = :id
     */
    public function delete(string $table, int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$table} WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }

    /**
     * DESCRIBE {$table} — dipakai filterColumns() untuk cegah mass-assignment
     * (data yang di-insert/update dibatasi cuma kolom yang benar-benar ada).
     */
    public function getTableColumns(string $table): array
    {
        $stmt = $this->db->prepare("DESCRIBE {$table}");
        $stmt->execute();

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
    }

    public function filterColumns(string $table, array $data): array
    {
        return array_intersect_key($data, array_flip($this->getTableColumns($table)));
    }

    /**
     * INSERT INTO {$table} (...) VALUES (...), otomatis buang key yang
     * bukan kolom asli tabel (lewat filterColumns) sebelum di-insert.
     *
     * @return int|false id baris baru, atau false kalau gagal
     */
    public function create(string $table, array $data)
    {
        $data = $this->filterColumns($table, $data);
        if (empty($data)) {
            return false;
        }

        $columns      = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $stmt = $this->db->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");

        if (!$stmt->execute($data)) {
            return false;
        }

        return (int) $this->db->lastInsertId();
    }

    /**
     * UPDATE {$table} SET ... WHERE id = :id, sama-sama lewat filterColumns
     * dulu. Kalau setelah difilter tidak ada kolom valid tersisa, dianggap
     * "tidak ada yang perlu diupdate" (bukan error).
     */
    public function update(string $table, int $id, array $data): bool
    {
        $data = $this->filterColumns($table, $data);
        if (empty($data)) {
            return true;
        }

        $set = implode(', ', array_map(fn($col) => "{$col} = :{$col}", array_keys($data)));
        $data['id'] = $id;

        $stmt = $this->db->prepare("UPDATE {$table} SET {$set} WHERE id = :id");

        return $stmt->execute($data);
    }

    /**
     * Ambil semua baris `sections` untuk satu halaman (page slug), urut
     * sort_order. Dipakai listing resource 'content-section' yang di-scope
     * per halaman (mis. cuma section milik page 'home').
     */
    public function getSectionsForPage(string $pageSlug): array
    {
        $stmt = $this->db->prepare('
            SELECT s.*
            FROM sections s
            JOIN pages p ON p.id = s.page_id
            WHERE p.slug = :slug
            ORDER BY s.sort_order ASC
        ');
        $stmt->execute(['slug' => $pageSlug]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPageIdBySlug(string $slug): int
    {
        $stmt = $this->db->prepare('SELECT id FROM pages WHERE slug = :slug LIMIT 1');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new \RuntimeException("Page dengan slug '{$slug}' tidak ditemukan.");
        }

        return (int) $row['id'];
    }

    /**
     * Cari satu section spesifik lewat page slug + section_key — dipakai
     * resourceEdit untuk 'content-section' karena section tidak punya
     * URL /id seperti resource lain, tapi /page/section_key.
     */
    public function findSection(string $pageSlug, string $sectionKey): ?array
    {
        $stmt = $this->db->prepare('
            SELECT s.*
            FROM sections s
            JOIN pages p ON p.id = s.page_id
            WHERE p.slug = :slug AND s.section_key = :key
            LIMIT 1
        ');
        $stmt->execute(['slug' => $pageSlug, 'key' => $sectionKey]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * INSERT INTO media (...) — dipakai setelah ImageUploader::upload()
     * berhasil mengkonversi file ke WebP, untuk mendaftarkan file itu
     * sebagai baris media yang bisa dihubungkan lewat media_relations.
     *
     * @param array    $fileMeta   hasil dari ImageUploader::upload()
     * @param int|null $uploadedBy id admin yang upload (utk kolom uploaded_by)
     * @return int id baris media yang baru dibuat
     */
    public function createMedia(array $fileMeta, ?int $uploadedBy = null): int
    {
        $id = $this->create('media', [
            'filename'          => $fileMeta['filename'],
            'original_filename' => $fileMeta['original_filename'] ?? $fileMeta['filename'],
            'path'              => $fileMeta['path'],
            'mime_type'         => $fileMeta['mime_type'],
            'size_bytes'        => $fileMeta['size_bytes'],
            'width'             => $fileMeta['width'] ?? null,
            'height'            => $fileMeta['height'] ?? null,
            'uploaded_by'       => $uploadedBy,
        ]);

        if ($id === false) {
            throw new \RuntimeException('Gagal menyimpan metadata media ke database.');
        }

        return $id;
    }

    /**
     * INSERT INTO media_relations — hubungkan satu media ke satu entity.
     */
    public function attachMedia(
        string $entityType,
        int $entityId,
        int $mediaId,
        string $usageType = 'gallery',
        int $sortOrder = 0
    ): bool {
        return $this->create('media_relations', [
            'media_id'    => $mediaId,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'usage_type'  => $usageType,
            'sort_order'  => $sortOrder,
        ]) !== false;
    }

    /**
     * Ganti cover satu entity: hapus relasi 'cover' yang lama (kalau ada),
     * baru pasang yang baru. Dibungkus transaksi supaya tidak ada momen
     * "entity tanpa cover sama sekali" kalau request gagal di tengah jalan.
     *
     * Kolom `media` fisik punya media lama SENGAJA tidak dihapus di sini —
     * sama seperti deleteMediaRelations(), pembersihan file yatim itu
     * urusan proses terpisah, bukan bagian dari alur ganti cover.
     */
    public function replaceCover(string $entityType, int $entityId, int $mediaId, string $usageType = 'cover'): bool
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'DELETE FROM media_relations WHERE entity_type = :entity_type AND entity_id = :entity_id AND usage_type = :usage_type'
            );
            $stmt->execute([
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'usage_type'  => $usageType,
            ]);

            $attached = $this->attachMedia($entityType, $entityId, $mediaId, $usageType, 0);

            $this->db->commit();

            return $attached;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Hapus semua baris media_relations milik satu entity. Tidak menghapus
     * baris 'media' fisiknya (file-nya) — sengaja, karena satu file media
     * berpotensi dipakai lebih dari satu tempat. Pembersihan file yatim
     * (orphan) sebaiknya jadi tugas terpisah (mis. cron/cleanup job),
     * bukan bagian dari alur delete resource.
     */
    public function deleteMediaRelations(string $entityType, int $entityId): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM media_relations WHERE entity_type = :entity_type AND entity_id = :entity_id"
        );

        return $stmt->execute([
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
        ]);
    }

    /**
     * Hapus satu baris resource SEKALIGUS relasi media-nya dalam satu
     * transaksi, supaya tidak ada kondisi setengah-jalan (baris resource
     * terhapus tapi media_relations-nya nyangkut, atau sebaliknya).
     *
     * @param string|null $entityType null kalau resource ini memang tidak
     *                                 pernah punya media (mis. administrator)
     */
    public function deleteWithMedia(string $table, int $id, ?string $entityType = null): bool
    {
        $this->db->beginTransaction();

        try {
            if ($entityType !== null) {
                $this->deleteMediaRelations($entityType, $id);
            }

            $deleted = $this->delete($table, $id);

            $this->db->commit();

            return $deleted;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Satu pemanggilan untuk count + paginate + (opsional) attach thumbnail,
     * berdasarkan satu entry $resourceMap. Dipakai controller supaya tidak
     * perlu tulis ulang logika "sort_order kalau ada, fallback id" dan
     * "attach thumbnail kalau kolomnya ada" di tiap tempat yang butuh list.
     *
     * @param array $config  satu entry dari $resourceMap — wajib punya 'table'
     *                       dan 'columns', opsional 'entity_type' kalau
     *                       resource ini punya kolom thumbnail_url.
     */
    public function getListForResource(array $config, int $page = 1, int $perPage = 10): array
    {
        $db    = Database::connection();
        $table = $config['table'];

        $entityType = $config['entity_type'] ?? null;
        $columnKeys = array_column($config['columns'] ?? [], 'key');

        // Urut sort_order dulu kalau kolomnya ada di resource ini (semua form
        // admin punya field "Urutan" yang percuma kalau listing-nya tidak
        // menghormati ini) — fallback ke created_at DESC (terbaru dulu) kalau
        // resource-nya memang tidak punya sort_order (mis. admins).
        $orderBy = in_array('sort_order', $columnKeys, true)
            ? 't.sort_order ASC'
            : 't.created_at DESC';

        $offset = ($page - 1) * $perPage;

        if ($entityType !== null) {
            // PENTING: filter usage_type = 'cover' DI DALAM kondisi JOIN
            // (bukan taruh di WHERE) — satu entity bisa punya banyak baris
            // media_relations (cover + gallery sekaligus). Tanpa filter ini,
            // LEFT JOIN akan mencocokkan SEMUA baris yang match, bikin satu
            // entity muncul berkali-kali (satu baris per foto) di hasil query.
            $sql = "
                SELECT t.*, m.path AS thumbnail_url
                FROM {$table} t
                LEFT JOIN media_relations mr
                       ON mr.entity_id = t.id
                      AND mr.entity_type = :entity_type
                      AND mr.usage_type = 'cover'
                LEFT JOIN media m ON m.id = mr.media_id
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset
            ";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':entity_type', $entityType, PDO::PARAM_STR);
        } else {
            // Resource tanpa entity_type (mis. administrator) — tidak perlu
            // JOIN ke media_relations sama sekali, tidak ada thumbnail utk ditarik.
            $sql = "SELECT t.* FROM {$table} t ORDER BY {$orderBy} LIMIT :limit OFFSET :offset";
            $stmt = $db->prepare($sql);
        }

        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $items = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        $countStmt = $db->prepare("SELECT COUNT(*) FROM {$table}");
        $countStmt->execute();
        $total = (int) $countStmt->fetchColumn();

        return [
            'items'   => $items,
            'total'   => $total,
            'page'    => $page,
            'perPage' => $perPage,
        ];
    }
    /**
     * Ambil N data teratas berdasarkan urutan (sort_order)
     */
    public function getTopItems(string $table, int $limit = 3): array
    {
        // Mengambil data yang aktif/published, diurutkan dari sort_order terkecil
        $sql = "SELECT * FROM {$table} 
            WHERE (status = 'published' OR is_active = 1) 
            ORDER BY sort_order ASC, id DESC 
            LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
    /**
     * Menukar nilai sort_order antara dua item di tabel yang sama
     */
    public function moveSortOrder(string $table, int $id, string $direction): bool
    {
        // 1. Ambil data item saat ini
        $sql  = "SELECT id, sort_order FROM {$table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $currentItem = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$currentItem) return false;

        $currentOrder = (int) ($currentItem['sort_order'] ?? $id);

        // 2. Cari item tetangga (Atas/Bawah)
        if ($direction === 'up') {
            // Cari 1 item yang sort_order-nya lebih kecil (di atasnya)
            $sqlNeighbor = "SELECT id, sort_order FROM {$table} 
                        WHERE sort_order < :order 
                        ORDER BY sort_order DESC LIMIT 1";
        } else {
            // Cari 1 item yang sort_order-nya lebih besar (di bawahnya)
            $sqlNeighbor = "SELECT id, sort_order FROM {$table} 
                        WHERE sort_order > :order 
                        ORDER BY sort_order ASC LIMIT 1";
        }

        $stmtNeighbor = $this->db->prepare($sqlNeighbor);
        $stmtNeighbor->execute([':order' => $currentOrder]);
        $neighborItem = $stmtNeighbor->fetch(\PDO::FETCH_ASSOC);

        // Jika tidak ada tetangga (misal sudah di paling atas/bawah), tidak ada yang diubah
        if (!$neighborItem) return false;

        // 3. Swap (Tukar) sort_order keduanya
        $neighborId    = (int) $neighborItem['id'];
        $neighborOrder = (int) $neighborItem['sort_order'];

        $this->db->beginTransaction();
        try {
            $updateSql = "UPDATE {$table} SET sort_order = :order WHERE id = :id";

            $stmtUpdate = $this->db->prepare($updateSql);
            $stmtUpdate->execute([':order' => $neighborOrder, ':id' => $id]);
            $stmtUpdate->execute([':order' => $currentOrder, ':id' => $neighborId]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    /**
     * Mengambil seluruh data settings dari database dan mengembalikannya
     * dalam bentuk key-value array associative: ['setting_key' => 'setting_value']
     *
     * @return array
     */
    public function getAllSettings(): array
    {
        $sql  = "SELECT setting_key, setting_value FROM settings";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // FETCH_KEY_PAIR secara otomatis mengubah 2 kolom hasil query
        // menjadi format key => value: ['village_name' => 'Desa Ketapang Raya', ...]
        $results = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        return $results ?: [];
    }
    /**
     * Memperbarui nilai setting berdasarkan setting_key.
     * Jika key belum ada, otomatis membuat baris baru.
     */
    public function updateSettingByKey(string $key, string $value): bool
    {
        // Cek apakah key sudah ada di DB
        $sqlCheck  = "SELECT COUNT(*) FROM settings WHERE setting_key = :key";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([':key' => $key]);
        $exists    = $stmtCheck->fetchColumn() > 0;

        if ($exists) {
            $sql  = "UPDATE settings SET setting_value = :val, updated_at = CURRENT_TIMESTAMP WHERE setting_key = :key";
        } else {
            $sql  = "INSERT INTO settings (setting_key, setting_value, setting_type, setting_group) VALUES (:key, :val, 'text', 'general')";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':key' => $key,
            ':val' => $value
        ]);
    }
    /**
     * Ambil semua data dari tabel yang berstatus 'published' (atau 'is_active' = 1)
     */
    public function getPublished(string $table): array
    {
        // Cek kolom tabel dulu untuk menyesuaikan WHERE condition
        $columns = $this->getTableColumns($table);

        if (in_array('status', $columns, true)) {
            $sql = "SELECT * FROM {$table} WHERE status = 'published' ORDER BY id DESC";
        } elseif (in_array('is_active', $columns, true)) {
            $sql = "SELECT * FROM {$table} WHERE is_active = 1 ORDER BY id DESC";
        } else {
            $sql = "SELECT * FROM {$table} ORDER BY id DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }
}
