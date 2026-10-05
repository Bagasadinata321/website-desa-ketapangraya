<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\PageSection;
use App\Models\Resource;

class DestinasiProdukController
{
    public function index(Request $request, Response $response)
    {
        $resourceModel = new Resource();

        // 1. Ambil data Section Statis untuk Halaman 'destinasi-produk'
        $sections = PageSection::getSectionsByPage('destinasi-produk');

        // 2. Ambil List Destinasi dari DB
        $destinasiResult = $resourceModel->getListForResource([
            'table'       => 'destinations',
            'entity_type' => 'destination',
            'columns'     => []
        ], 1, 10);

        // 3. Ambil List Produk dari DB
        $produkResult = $resourceModel->getListForResource([
            'table'       => 'products',
            'entity_type' => 'product',
            'columns'     => []
        ], 1, 10);

        return $response->view('public.produk', [
            'currentPage'   => 'destinasi-produk',
            'title'         => 'Destinasi & Produk - Desa Ketapang Raya',
            'sections'      => $sections,
            'destinasiList' => $destinasiResult['items'] ?? [],
            'produkList'    => $produkResult['items'] ?? [],
        ], 'main');
    }
}
