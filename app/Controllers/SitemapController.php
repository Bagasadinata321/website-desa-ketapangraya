<?php

namespace App\Controllers;

use App\Core\Response;
use App\Models\Resource;

class SitemapController
{
    public function index(Response $response)
    {
        header('Content-Type: application/xml; charset=utf-8');

        $resourceModel = new Resource();

        // Ambil data dari database yang statusnya published/aktif
        $destinasi = $resourceModel->getPublished('destinations');
        $produk    = $resourceModel->getPublished('products');

        $baseUrl = url('');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Halaman Statis Utama
        $staticPages = ['', '/test-live/about', '/test-live/produk'];
        foreach ($staticPages as $page) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . $page . '</loc>';
            $xml .= '<changefreq>weekly</changefreq>';
            $xml .= '<priority>1.0</priority>';
            $xml .= '</url>';
        }

        // Halaman Detail Destinasi (Dinamis dari Database)
        foreach ($destinasi as $item) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/destinasi/' . $item['slug'] . '</loc>';
            $xml .= '<lastmod>' . date('Y-m-d', strtotime($item['updated_at'] ?? 'now')) . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        // Halaman Detail Produk (Dinamis dari Database)
        foreach ($produk as $item) {
            $xml .= '<url>';
            $xml .= '<loc>' . $baseUrl . '/produk/' . $item['slug'] . '</loc>';
            $xml .= '<lastmod>' . date('Y-m-d', strtotime($item['updated_at'] ?? 'now')) . '</lastmod>';
            $xml .= '<changefreq>monthly</changefreq>';
            $xml .= '<priority>0.8</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';

        echo $xml;
        exit;
    }
}
