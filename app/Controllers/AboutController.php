<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\PageSection;
use App\Models\Program; // Import model Program
use App\Models\Resource;

class AboutController
{
    public function index(Request $request, Response $response)
    {
        $resourceModel = new Resource();

        // 1. Ambil data Section Statis untuk Halaman 'tentang-kami'
        $sections = PageSection::getSectionsByPage('tentang-kami');

        // 2. Ambil data Pemerintah Desa (Tabel 'officials')
        $staffResult = $resourceModel->getListForResource([
            'table'       => 'officials',
            'entity_type' => 'official',
            'columns'     => []
        ], 1, 20);
        $staffList = $staffResult['items'] ?? [];

        // 3. Ambil data Tim KKN Mahasiswa (Tabel 'students')
        $teamResult = $resourceModel->getListForResource([
            'table'       => 'students',
            'entity_type' => 'student',
            'columns'     => []
        ], 1, 20);
        $teamList = $teamResult['items'] ?? [];

        // 4. Ambil data Program KKN (Tabel 'programs' + galeri dari Program model)
        $kknList = Program::getAll();

        // 5. Render View 'public.about'
        return $response->view('public.about', [
            'currentPage' => 'tentang-kami',
            'title'       => 'Tentang Kami - Desa Ketapang Raya',
            'sections'    => $sections,
            'staffList'   => $staffList,
            'teamList'    => $teamList,
            'kknList'     => $kknList,
        ], 'main');
    }
}
