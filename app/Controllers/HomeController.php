<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Destination;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\Resource;

class HomeController extends BaseController
{
    /**
     * Halaman Beranda (Home)
     * Mengambil Top 3 data destinasi, potensi, dan produk berdasarkan urutan.
     */
    public function index(Request $request, Response $response)
    {
        $resourceModel = new Resource();

        // 1. Ambil data Section Statis untuk Halaman 'home' (Hero, Mengenal Desa, Info Singkat)
        // Menggunakan PageSection agar gambar section/hero di-load dengan benar
        $sections = PageSection::getSectionsByPage('home');

        // 2. Ambil Top 3 Destinasi Unggulan (Include Gambar Cover)
        $destinasiResult = $resourceModel->getListForResource([
            'table'       => 'destinations',
            'entity_type' => 'destination',
            'columns'     => []
        ], page: 1, perPage: 3);
        $destinasiList = $destinasiResult['items'] ?? [];

        // 3. Ambil Top 3 Potensi Desa / Highlights (Include Gambar Cover)
        $potensiResult = $resourceModel->getListForResource([
            'table'       => 'highlights',
            'entity_type' => 'highlight',
            'columns'     => []
        ], page: 1, perPage: 6);
        $highlights = $potensiResult['items'] ?? [];

        // 4. Ambil Top 3 Produk Desa (Include Gambar Cover)
        $produkResult = $resourceModel->getListForResource([
            'table'       => 'products',
            'entity_type' => 'product',
            'columns'     => []
        ], page: 1, perPage: 3);
        $produkList = $produkResult['items'] ?? [];

        // Render View 'home'
        return $response->view('home', [
            'currentPage'   => 'home',
            'title'         => 'Beranda - Desa Ketapang Raya',
            'sections'      => $sections,
            'destinasiList' => $destinasiList,
            'highlights'    => $highlights,
            'produkList'    => $produkList,
        ], 'main');
    }

    /**
     * Halaman Detail Destinasi
     */
    public function showDestinasiDetail(Request $request, Response $response, $slug)
    {
        $resourceModel = new Destination();

        $destinasi = $resourceModel->findBySlug($slug);

        if (!$destinasi) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', []);
            return;
        } else {
            // Ambil semua URL galeri (usage_type = 'gallery') milik destinasi ini
            $destinasi['gallery'] = Destination::getGallery((int) $destinasi['id']);
        }

        return $response->view('public.detail', [
            'currentPage' => 'destinasi',
            'pageType'    => 'destinasi',
            'page'        => 'potensi',
            'data'        => $destinasi,
            'title'       => $destinasi['title'] ?? 'Detail Destinasi'
        ], 'main');
    }

    /**
     * Halaman Detail Produk
     */
    public function showProdukDetail(Request $request, Response $response, $slug)
    {
        $resourceModel = new Product();

        $produk = $resourceModel->findBySlug($slug);

        if (!$produk) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', []);
            return;
        } else {
            // Ambil semua URL galeri (usage_type = 'gallery') milik destinasi ini
            $produk['gallery'] = Destination::getGallery((int) $produk['id']);
        }

        return $response->view('public.detail', [
            'currentPage' => 'produk',
            'pageType'    => 'produk',
            'page'        => 'potensi',
            'data'        => $produk,
            'title'       => $produk['title'] ?? 'Detail Produk'
        ], 'main');
    }
}
