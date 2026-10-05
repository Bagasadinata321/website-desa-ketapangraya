<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Resource;

class PublicController
{
    /**
     * Halaman Detail Destinasi / Produk
     */
    public function showDetail(Request $request, Response $response, $type, $slug)
    {
        // 1. Validasi tipe resource (hanya destinasi atau produk)
        if (!in_array($type, ['destinasi', 'produk'], true)) {
            $response->setStatusCode(404);
            $response->view('errors.404');
            return;
        }

        $tableName  = ($type === 'destinasi') ? 'destinations' : 'products';
        $entityType = ($type === 'destinasi') ? 'destination'  : 'product';

        $resourceModel = new Resource();

        // 2. Ambil data utama berdasarkan slug
        $item = $resourceModel->findBySlug($tableName, $slug);

        if (!$item || (isset($item['status']) && $item['status'] !== 'published')) {
            $response->setStatusCode(404);
            $response->view('errors.404');
            return;
        }

        // 3. Ambil Cover Gambar & Galeri Foto
        $coverUrl = $resourceModel->getCoverUrl($entityType, (int) $item['id']);
        $gallery  = $resourceModel->getGalleryUrls($entityType, (int) $item['id']);

        // 4. Susun array $data sesuai ekspektasi detail.php
        $data = [
            'title'     => $item['title'] ?? '',
            'secondary' => ($type === 'destinasi') ? ($item['location'] ?? '') : ($item['price_info'] ?? ''),
            'excerpt'   => $item['excerpt'] ?? '',
            'content'   => $item['content'] ?? '[]', // Kirim string JSON atau array
            'cover_url' => $coverUrl,
            'gallery'   => $gallery,
        ];

        // 5. Ambil data item terkait (related items) dari database
        $relatedDb = $resourceModel->getRelatedItems($tableName, (int) $item['id'], 3);
        $relatedItems = [];

        foreach ($relatedDb as $rel) {
            $relCover = $resourceModel->getCoverUrl($entityType, (int) $rel['id']);
            $relatedItems[] = [
                'title'       => $rel['title'],
                'excerpt'     => $rel['excerpt'],
                'href'        => url($type . '/' . $rel['slug']),
                'image_url'   => $relCover,
                'image_label' => 'Foto: ' . $rel['title'],
            ];
        }

        // 6. Render View detail.php dengan membawa variabel yang dibutuhkan
        $response->view('public.detail', [
            'pageType'     => $type,
            'backHref'     => url('destinasi-produk'), // Halaman listing utama
            'data'         => $data,
            'relatedItems' => $relatedItems,
        ]);
    }
}
