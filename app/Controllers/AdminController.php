<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Core\Imageuploader;
use App\Models\Admin;
use App\Models\Resource;
use App\Models\ClientDetail;
use App\Models\ClientGambar;
use App\Models\Katalog;
use App\Models\Clients;
use App\Models\Guest;
use App\Models\TemplateAddons;
use App\Models\TemplateSlot;

class AdminController
{

    public function index(Request $request, Response $response)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        $admin = $_SESSION['admin'];
        $currentPage = 'dashboard';

        // Inisialisasi Resource model untuk query database
        $resourceModel = new Resource();

        // Hitung total data masing-masing tabel dari DB
        $totalDestinasi   = $resourceModel->count('destinations');
        $totalProduk      = $resourceModel->count('products');
        $totalPotensiDesa = $resourceModel->count('highlights');
        $totalAdmin       = $resourceModel->count('admins');

        // Susun array statistik
        $stats = [
            [
                'label' => 'Destinasi',
                'value' => $totalDestinasi,
                'icon'  => 'bi-map',
                'link'  => url('admin/destinasi/list')
            ],
            [
                'label' => 'Produk',
                'value' => $totalProduk,
                'icon'  => 'bi-box-seam',
                'link'  => url('admin/produk/list')
            ],
            [
                'label' => 'Potensi Desa',
                'value' => $totalPotensiDesa,
                'icon'  => 'bi-star',
                'link'  => url('admin/potensi-desa/list')
            ],
            [
                'label' => 'Jumlah Admin',
                'value' => $totalAdmin,
                'icon'  => 'bi-person-badge',
                'link'  => url('admin/administrator/list')
            ],
        ];

        $response->view('admin.dashboard', [
            'currentPage' => $currentPage,
            'admin'       => $admin,
            'stats'       => $stats // Kirim variabel $stats ke view dashboard
        ], 'admin');
    }

    public function showHomepage(Request $request, Response $response)
    {
        $currentPage = 'homepage';
        $response->view('admin.homepage-sections', [
            'currentPage' => $currentPage
        ], 'admin');
    }

    public function showDestinasi(Request $request, Response $response)
    {
        $currentPage = 'destinasi';
        $response->view('admin.destinasi-list', ['currentPage' => $currentPage], 'admin');
    }

    public function pagesEdit(Request $request, Response $response, $content, $id)
    {
        $currentPage = 'destinasi';
        $response->view('admin.destinasi-form', [
            'currentPage' => $currentPage,
            'destinasi_id' => $id
        ], 'admin');
    }

    private array $resourceMap = [

        'destinasi' => [
            'table'       => 'destinations',
            'entity_type' => 'destination',
            'form_type'   => 'lengkap',
            'label'       => 'Destinasi',
            'columns' => [
                ['key' => 'thumbnail_url', 'label' => 'Gambar',   'type' => 'thumbnail'],
                ['key' => 'title',         'label' => 'Judul',    'type' => 'text-bold'],
                ['key' => 'location',      'label' => 'Lokasi',   'type' => 'text'],
                ['key' => 'status',        'label' => 'Status',   'type' => 'status', 'status_map' => [
                    'published' => ['label' => 'Published', 'class' => 'success'],
                    'draft'     => ['label' => 'Draft',      'class' => 'neutral'],
                ]],
                ['key' => 'sort_order',    'label' => 'Urutan',   'type' => 'text'],
            ],
            'form_meta' => [
                'secondary_column'      => 'location',
                'secondary_label'       => 'Lokasi',
                'secondary_placeholder' => 'Ketapang Raya, Lombok',
                'status_type'           => 'published_draft',
                'show_featured'         => true,
            ],
        ],

        'produk' => [
            'table'       => 'products',
            'entity_type' => 'product',
            'form_type'   => 'lengkap',
            'label'       => 'Produk',
            'columns' => [
                ['key' => 'thumbnail_url', 'label' => 'Gambar',      'type' => 'thumbnail'],
                ['key' => 'title',         'label' => 'Judul',       'type' => 'text-bold'],
                ['key' => 'price_info',    'label' => 'Info Harga',  'type' => 'text'],
                ['key' => 'status',        'label' => 'Status',      'type' => 'status', 'status_map' => [
                    'published' => ['label' => 'Published', 'class' => 'success'],
                    'draft'     => ['label' => 'Draft',      'class' => 'neutral'],
                ]],
                ['key' => 'sort_order',    'label' => 'Urutan',      'type' => 'text'],
            ],
            'form_meta' => [
                'secondary_column'      => 'price_info',
                'secondary_label'       => 'Info Harga',
                'secondary_placeholder' => 'Hubungi kami',
                'status_type'           => 'published_draft',
                'show_featured'         => false,
            ],
        ],

        'potensi-desa' => [
            'table'       => 'highlights',
            'entity_type' => 'highlight',
            'form_type'   => 'ringkas',
            'label'       => 'Potensi Desa',
            'columns' => [
                ['key' => 'thumbnail_url', 'label' => 'Gambar', 'type' => 'thumbnail'],
                ['key' => 'title',         'label' => 'Judul',  'type' => 'text-bold'],
                ['key' => 'is_active',     'label' => 'Status', 'type' => 'status', 'status_map' => [
                    1 => ['label' => 'Aktif',    'class' => 'success'],
                    0 => ['label' => 'Nonaktif', 'class' => 'neutral'],
                ]],
                ['key' => 'sort_order',    'label' => 'Urutan', 'type' => 'text'],
            ],
        ],

        'perangkat-desa' => [
            'table'       => 'officials',
            'entity_type' => 'official',
            'form_type'   => 'profil',
            'label'       => 'Perangkat Desa',
            'columns' => [
                ['key' => 'thumbnail_url', 'label' => 'Foto',    'type' => 'thumbnail'],
                ['key' => 'name',          'label' => 'Nama',    'type' => 'text-bold'],
                ['key' => 'position',      'label' => 'Jabatan', 'type' => 'text'],
                ['key' => 'is_active',     'label' => 'Status',  'type' => 'status', 'status_map' => [
                    1 => ['label' => 'Aktif',    'class' => 'success'],
                    0 => ['label' => 'Nonaktif', 'class' => 'neutral'],
                ]],
                ['key' => 'sort_order',    'label' => 'Urutan',  'type' => 'text'],
            ],
            'form_meta' => [
                'primary_label'    => 'Nama',
                'show_photo'       => true,
                'photo_label'      => 'Foto Profil',
                'show_description' => true,
                'show_sort_order'  => true,
                'extra_fields'     => [
                    ['key' => 'position', 'label' => 'Jabatan', 'type' => 'text', 'placeholder' => 'Sekretaris Desa'],
                ],
            ],
        ],

        'mitra' => [
            'table'       => 'partners',
            'entity_type' => 'partner',
            'form_type'   => 'profil',
            'label'       => 'Mitra',
            'columns' => [
                ['key' => 'thumbnail_url', 'label' => 'Logo',     'type' => 'thumbnail'],
                ['key' => 'name',          'label' => 'Nama',     'type' => 'text-bold'],
                ['key' => 'category',      'label' => 'Kategori', 'type' => 'text'],
                ['key' => 'is_active',     'label' => 'Status',   'type' => 'status', 'status_map' => [
                    1 => ['label' => 'Aktif',    'class' => 'success'],
                    0 => ['label' => 'Nonaktif', 'class' => 'neutral'],
                ]],
                ['key' => 'sort_order',    'label' => 'Urutan',   'type' => 'text'],
            ],
            'form_meta' => [
                'primary_label'    => 'Nama',
                'show_photo'       => true,
                'photo_label'      => 'Logo',
                'show_description' => false,
                'show_sort_order'  => true,
                'extra_fields'     => [
                    ['key' => 'category',    'label' => 'Kategori', 'type' => 'text'],
                    ['key' => 'website_url', 'label' => 'Website',  'type' => 'text'],
                ],
            ],
        ],

        'program-kkn' => [
            'table'       => 'programs',
            'entity_type' => 'program',
            'form_type'   => 'lengkap',
            'label'       => 'Program KKN',
            'columns' => [
                ['key' => 'thumbnail_url', 'label' => 'Gambar',  'type' => 'thumbnail'],
                ['key' => 'title',         'label' => 'Judul',   'type' => 'text-bold'],
                ['key' => 'period_label',  'label' => 'Periode', 'type' => 'text'],
                ['key' => 'is_active',     'label' => 'Status',  'type' => 'status', 'status_map' => [
                    1 => ['label' => 'Aktif',    'class' => 'success'],
                    0 => ['label' => 'Nonaktif', 'class' => 'neutral'],
                ]],
                ['key' => 'sort_order',    'label' => 'Urutan',  'type' => 'text'],
            ],
            'form_meta' => [
                'secondary_column'      => 'period_label',
                'secondary_label'       => 'Periode',
                'secondary_placeholder' => 'Semester Genap 2026',
                'status_type'           => 'active_inactive',
                'show_featured'         => false,
            ],
        ],

        'mahasiswa-kkn' => [
            'table'       => 'students',
            'entity_type' => 'student',
            'form_type'   => 'profil',
            'label'       => 'Mahasiswa KKN',
            'columns' => [
                ['key' => 'thumbnail_url', 'label' => 'Foto',   'type' => 'thumbnail'],
                ['key' => 'name',          'label' => 'Nama',   'type' => 'text-bold'],
                ['key' => 'position',      'label' => 'Peran',  'type' => 'text'],
                ['key' => 'campus',        'label' => 'Kampus', 'type' => 'text'],
                ['key' => 'is_active',     'label' => 'Status', 'type' => 'status', 'status_map' => [
                    1 => ['label' => 'Aktif',    'class' => 'success'],
                    0 => ['label' => 'Nonaktif', 'class' => 'neutral'],
                ]],
                ['key' => 'sort_order',    'label' => 'Urutan', 'type' => 'text'],
            ],
            'form_meta' => [
                'primary_label'    => 'Nama',
                'show_photo'       => true,
                'photo_label'      => 'Foto',
                'show_description' => false,
                'show_sort_order'  => true,
                'extra_fields'     => [
                    ['key' => 'position', 'label' => 'Peran/Divisi', 'type' => 'text', 'placeholder' => 'Ketua Tim'],
                    ['key' => 'major',    'label' => 'Jurusan',      'type' => 'text'],
                    ['key' => 'campus',   'label' => 'Kampus',       'type' => 'text'],
                    ['key' => 'nim',      'label' => 'NIM',          'type' => 'text'],
                ],
            ],
        ],

        'administrator' => [
            'table'     => 'admins',
            'form_type' => 'profil',
            'roles'     => ['Kepala Admin'],
            'label'     => 'Administrator',
            'columns' => [
                ['key' => 'username', 'label' => 'Username', 'type' => 'text-bold'],
                ['key' => 'email',    'label' => 'Email',    'type' => 'text'],

                // MAPPING ROLE (Sesuai Enum DB: 'Kepala Admin' & 'Perangkat Desa')
                ['key' => 'role',     'label' => 'Role',     'type' => 'status', 'status_map' => [
                    'Kepala Admin'   => ['label' => 'Kepala Admin',   'class' => 'success'],
                    'Perangkat Desa' => ['label' => 'Perangkat Desa', 'class' => 'neutral'],
                ]],

                // MAPPING STATUS PENDAFTARAN (Sesuai Enum DB: 'status' => 'pending', 'approved', 'rejected')
                ['key' => 'status',   'label' => 'Status Pendaftaran', 'type' => 'status', 'status_map' => [
                    'pending'  => ['label' => 'Pending',  'class' => 'warning'],  // Warna Kuning
                    'approved' => ['label' => 'Approved', 'class' => 'success'],  // Warna Hijau
                    'rejected' => ['label' => 'Rejected', 'class' => 'danger'],   // Warna Merah
                ]],
            ],
            'form_meta' => [
                'primary_label'    => 'Nama',
                'show_photo'       => false,
                'show_description' => false,
                'show_sort_order'  => false,
                'extra_fields'     => [
                    ['key' => 'username', 'label' => 'Username', 'type' => 'text'],
                    ['key' => 'email',    'label' => 'Email',    'type' => 'email'],
                    ['key' => 'password', 'label' => 'Password', 'type' => 'password'],
                    ['key' => 'role',     'label' => 'Role',     'type' => 'select', 'options' => [
                        'Perangkat Desa' => 'Perangkat Desa',
                        'Kepala Admin'   => 'Kepala Admin',
                    ]],
                    ['key' => 'status',   'label' => 'Status Pendaftaran', 'type' => 'select', 'options' => [
                        'pending'  => 'Pending (Menunggu Persetujuan)',
                        'approved' => 'Approved (Diterima)',
                        'rejected' => 'Rejected (Ditolak)',
                    ]],
                ],
            ],
        ],
        'settings' => [
            'table'     => 'settings',
            'form_type' => 'profil',
            'roles'     => ['Kepala Admin'],
            'label'     => 'Pengaturan Website',
            'order_by'  => 'id ASC',
            'columns'   => [
                ['key' => 'setting_key',   'label' => 'Kunci Pengaturan', 'type' => 'text-bold'],
                ['key' => 'setting_value', 'label' => 'Nilai',            'type' => 'text'],
                ['key' => 'setting_group', 'label' => 'Grup',             'type' => 'status', 'status_map' => [
                    'general'     => ['label' => 'Umum',     'class' => 'neutral'],
                    'contact'     => ['label' => 'Kontak',   'class' => 'neutral'],
                    'social'      => ['label' => 'Medsos',   'class' => 'neutral'],
                    'email'       => ['label' => 'Email',    'class' => 'neutral'],
                    'demographics' => ['label' => 'Demografi', 'class' => 'neutral'],
                    'geography'   => ['label' => 'Geografi', 'class' => 'neutral'],
                ]],
            ],
            'form_meta' => [
                'primary_label'    => 'Pengaturan',
                'show_photo'       => false,
                'show_description' => false,
                'show_sort_order'  => false,
                'extra_fields'     => [
                    // INFORMASI UMUM
                    ['key' => 'village_name',      'label' => 'Nama Desa',            'type' => 'text'],
                    ['key' => 'village_logo',      'label' => 'Logo Desa',            'type' => 'file'],
                    ['key' => 'district',          'label' => 'Kecamatan',            'type' => 'text'],
                    ['key' => 'regency',           'label' => 'Kabupaten',            'type' => 'text'],
                    ['key' => 'province',          'label' => 'Provinsi',             'type' => 'text'],
                    ['key' => 'postal_code',       'label' => 'Kode Pos',             'type' => 'text'],

                    // KONTAK & ALAMAT
                    ['key' => 'office_address',    'label' => 'Alamat Kantor Desa',   'type' => 'textarea'],
                    ['key' => 'village_email',     'label' => 'Email Resmi Desa',     'type' => 'email'],
                    ['key' => 'village_phone',     'label' => 'Telepon / WhatsApp',   'type' => 'text'],

                    // MEDIA SOSIAL & PETA
                    ['key' => 'social_instagram',  'label' => 'URL Instagram',        'type' => 'text'],
                    ['key' => 'social_facebook',   'label' => 'URL Facebook',         'type' => 'text'],
                    ['key' => 'social_youtube',    'label' => 'URL YouTube',          'type' => 'text'],
                    ['key' => 'footer_map_iframe', 'label' => 'Embed Iframe Maps',    'type' => 'textarea'],
                    ['key' => 'footer_credit',     'label' => 'Teks Credit Footer',   'type' => 'text'],

                    // EMAIL NOTIFIKASI
                    ['key' => 'superadmin_email',  'label' => 'Email Super Admin',    'type' => 'email'],
                ],
            ],
        ],
        'konten-home' => [
            'table'     => 'sections',
            'page_slug' => 'home',
            'form_type' => 'section',
            'label'     => 'Konten Homepage',
            'sections'  => [
                ['key' => 'hero', 'label' => 'Hero', 'locked' => false, 'fields' => [
                    ['key' => 'subtitle',    'label' => 'Eyebrow',       'type' => 'text', 'placeholder' => 'Desa Ketapang Raya'],
                    ['key' => 'title',       'label' => 'Judul (H1)',    'type' => 'text'],
                    ['key' => 'description', 'label' => 'Deskripsi',     'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Gambar Latar',  'type' => 'image'],
                ]],
                ['key' => 'mengenal_desa', 'label' => 'Mengenal Desa', 'locked' => false, 'fields' => [
                    ['key' => 'title',       'label' => 'Judul',     'type' => 'text'],
                    ['key' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Gambar',    'type' => 'image'],
                ]],
                ['key' => 'potensi_desa',        'label' => 'Potensi Desa (preview)',  'locked' => true, 'lockedTarget' => 'potensi-desa'],
                ['key' => 'destinasi_unggulan',  'label' => 'Destinasi Unggulan',      'locked' => true, 'lockedTarget' => 'destinasi'],
                ['key' => 'informasi_singkat', 'label' => 'Info Singkat (statistik)', 'locked' => false, 'fields' => [
                    ['key' => 'description', 'label' => 'Teks Pengantar', 'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Gambar Latar',   'type' => 'image'],
                    ['key' => 'stats',       'label' => 'Statistik',      'type' => 'repeater', 'item_fields' => [
                        ['key' => 'number', 'label' => 'Angka', 'type' => 'text', 'placeholder' => '1499'],
                        ['key' => 'label',  'label' => 'Label',  'type' => 'text', 'placeholder' => 'Jumlah KK'],
                    ]],
                ]],
            ],
        ],

        'konten-tentang-kami' => [
            'table'     => 'sections',
            'page_slug' => 'tentang-kami',
            'form_type' => 'section',
            'label'     => 'Konten Tentang Kami',
            'sections'  => [
                ['key' => 'hero', 'label' => 'Hero', 'locked' => false, 'fields' => [
                    ['key' => 'subtitle',    'label' => 'Eyebrow',      'type' => 'text', 'placeholder' => 'Tentang Kami'],
                    ['key' => 'title',       'label' => 'Judul (H1)',   'type' => 'text'],
                    ['key' => 'description', 'label' => 'Deskripsi',    'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Gambar Latar', 'type' => 'image'],
                ]],
                ['key' => 'profil_desa', 'label' => 'Profil Desa', 'locked' => false, 'fields' => [
                    ['key' => 'title',       'label' => 'Judul',     'type' => 'text'],
                    ['key' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Gambar',    'type' => 'image'],
                ]],
                ['key' => 'pemerintah_desa', 'label' => 'Pemerintah Desa (preview)', 'locked' => true, 'lockedTarget' => 'perangkat-desa'],
                ['key' => 'tim_kkn',      'label' => 'Tim KKN (preview)',   'locked' => true, 'lockedTarget' => 'kkn'],
                ['key' => 'program_kkn_slider', 'label' => 'Program & Kegiatan KKN (preview)', 'locked' => true, 'lockedTarget' => 'kkn'],
                ['key' => 'kolaborasi', 'label' => 'Kolaborasi Untuk Desa', 'locked' => false, 'fields' => [
                    ['key' => 'title',       'label' => 'Judul',     'type' => 'text'],
                    ['key' => 'description', 'label' => 'Deskripsi', 'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Gambar',    'type' => 'image'],
                ]],
            ],
        ],

        'konten-destinasi-produk' => [
            'table'     => 'sections',
            'page_slug' => 'destinasi-produk',
            'form_type' => 'section',
            'label'     => 'Konten Destinasi & Produk',
            'sections'  => [
                ['key' => 'hero', 'label' => 'Hero', 'locked' => false, 'fields' => [
                    ['key' => 'subtitle',    'label' => 'Eyebrow',      'type' => 'text', 'placeholder' => 'Potensi Desa'],
                    ['key' => 'title',       'label' => 'Judul (H1)',   'type' => 'text'],
                    ['key' => 'description', 'label' => 'Deskripsi',    'type' => 'textarea'],
                    ['key' => 'image',       'label' => 'Gambar Latar', 'type' => 'image'],
                ]],
                ['key' => 'pengantar', 'label' => 'Pengantar Potensi Desa', 'locked' => false, 'fields' => [
                    ['key' => 'subtitle',      'label' => 'Eyebrow',   'type' => 'text', 'placeholder' => 'Potensi Desa'],
                    ['key' => 'title',         'label' => 'Judul',     'type' => 'text'],
                    ['key' => 'description',   'label' => 'Paragraf 1', 'type' => 'textarea'],
                    ['key' => 'description_2', 'label' => 'Paragraf 2', 'type' => 'textarea'],
                ]],
                ['key' => 'destinasi_wisata',  'label' => 'Destinasi Wisata (list)', 'locked' => true, 'lockedTarget' => 'destinasi'],
                ['key' => 'produk_unggulan',   'label' => 'Produk Unggulan (list)',  'locked' => true, 'lockedTarget' => 'produk'],
                ['key' => 'langkah_ekowisata', 'label' => 'Langkah Menuju Ekowisata', 'locked' => false, 'fields' => [
                    ['key' => 'subtitle',    'label' => 'Eyebrow', 'type' => 'text', 'placeholder' => 'Masa Depan Desa'],
                    ['key' => 'title',       'label' => 'Judul',   'type' => 'text'],
                    ['key' => 'description', 'label' => 'Paragraf', 'type' => 'textarea'],
                ]],
                ['key' => 'quote', 'label' => 'Quote Penutup', 'locked' => false, 'fields' => [
                    ['key' => 'description', 'label' => 'Teks Kutipan', 'type' => 'textarea'],
                ]],
            ],
        ],

    ];

    // =========================================================
    //  LIST
    // =========================================================

    public function resourceList(Request $request, Response $response, $halaman)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        if (!isset($this->resourceMap[$halaman])) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $config = $this->resourceMap[$halaman];
        $admin  = $_SESSION['admin'];

        if (!$this->authorizeRole($config, $admin)) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        if ($config['form_type'] === 'section') {
            $this->renderSectionList($response, $config, $halaman, $admin);
            return;
        }

        $page    = max(1, (int) ($request->input('page') ?? 1));
        $perPage = 10;

        $resourceModel = new Resource();
        $result        = $resourceModel->getListForResource($config, $page, $perPage);

        $response->view('admin.list', [
            'currentPage'      => $halaman,
            'admin'            => $admin,
            'resourceKey'      => $halaman,
            'pageLabel'        => $config['label'],
            'breadcrumbParent' => 'Data Konten',
            'columns'          => $config['columns'],
            'items'            => $result['items'],
            'pagination'       => $this->buildPagination($result['total'], $page, $perPage),
        ], 'admin');
    }

    public function kknList(Request $request, Response $response)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        $admin   = $_SESSION['admin'];
        $perPage = 10;

        $studentsConfig = $this->resourceMap['mahasiswa-kkn'];
        $programsConfig = $this->resourceMap['program-kkn'];

        $resourceModel = new Resource();

        $studentsPage = max(1, (int) ($request->input('mahasiswa_page') ?? 1));
        $programsPage = max(1, (int) ($request->input('program_page') ?? 1));

        $studentsResult = $resourceModel->getListForResource($studentsConfig, $studentsPage, $perPage);
        $programsResult = $resourceModel->getListForResource($programsConfig, $programsPage, $perPage);

        $blocks = [
            [
                'resourceKey'     => 'mahasiswa-kkn',
                'pageLabel'       => $studentsConfig['label'],
                'blockTitle'      => 'Anggota Tim KKN',
                'columns'         => $studentsConfig['columns'],
                'items'           => $studentsResult['items'],
                'pagination'      => $this->buildPagination($studentsResult['total'], $studentsPage, $perPage),
                'paginationParam' => 'mahasiswa_page',
            ],
            [
                'resourceKey'     => 'program-kkn',
                'pageLabel'       => $programsConfig['label'],
                'blockTitle'      => 'Program KKN',
                'columns'         => $programsConfig['columns'],
                'items'           => $programsResult['items'],
                'pagination'      => $this->buildPagination($programsResult['total'], $programsPage, $perPage),
                'paginationParam' => 'program_page',
            ],
        ];

        $response->view('admin.kkn-list', [
            'currentPage'      => 'kkn',
            'admin'            => $admin,
            'pageLabel'        => 'Tim & Program KKN',
            'breadcrumbParent' => 'Data Konten',
            'blocks'           => $blocks,
        ], 'admin');
    }

    private function buildPagination(int $total, int $page, int $perPage): array
    {
        return [
            'from'         => $total === 0 ? 0 : (($page - 1) * $perPage) + 1,
            'to'           => min($page * $perPage, $total),
            'total'        => $total,
            'current_page' => $page,
            'total_pages'  => max(1, (int) ceil($total / $perPage)),
        ];
    }

    private function renderSectionList(Response $response, array $config, string $halaman, array $admin): void
    {
        $resourceModel = new Resource();
        $dbSections    = $resourceModel->getSectionsForPage($config['page_slug']);

        $dbByKey = [];
        foreach ($dbSections as $row) {
            $dbByKey[$row['section_key']] = $row;
        }

        $items = [];
        foreach ($config['sections'] as $sectionDef) {
            $dbRow = $dbByKey[$sectionDef['key']] ?? null;

            $items[] = [
                'section_key'   => $sectionDef['key'],
                'label'         => $sectionDef['label'],
                'title'         => $dbRow['title'] ?? '(belum diisi)',
                'locked'        => $sectionDef['locked'],
                'locked_target' => $sectionDef['lockedTarget'] ?? null,
                'updated_at'    => $dbRow['updated_at'] ?? null,
            ];
        }

        $columns = [
            ['key' => 'label',      'label' => 'Section',          'type' => 'text-bold'],
            ['key' => 'title',      'label' => 'Judul Saat Ini',   'type' => 'text'],
            ['key' => 'locked',     'label' => 'Sumber',           'type' => 'locked-badge'],
            ['key' => 'updated_at', 'label' => 'Update Terakhir',  'type' => 'datetime'],
        ];

        $response->view('admin.list', [
            'currentPage'      => $halaman,
            'admin'            => $admin,
            'resourceKey'      => $halaman,
            'pageLabel'        => $config['label'],
            'breadcrumbParent' => 'Data Konten',
            'columns'          => $columns,
            'items'            => $items,
            'pagination'       => null,
            'rowKey'           => 'section_key',
        ], 'admin');
    }

    // =========================================================
    //  EDIT
    // =========================================================

    public function resourceEdit(Request $request, Response $response, $halaman, $id)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        if (!isset($this->resourceMap[$halaman]) || empty($this->resourceMap[$halaman]['form_type'])) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $config = $this->resourceMap[$halaman];
        $admin  = $_SESSION['admin'];

        if (!$this->authorizeRole($config, $admin)) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        $resourceModel = new Resource();
        $tableName     = $config['table'] ?? 'sections';
        $item          = $resourceModel->find($tableName, (int) $id);

        if (!$item) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $data = $this->buildFormData($item, $config, $resourceModel);

        $this->renderForm($response, $config, $halaman, $admin, $data, true);
    }

    // =========================================================
    //  EDIT SECTION (khusus resource form_type='section')
    // =========================================================

    public function sectionEdit(Request $request, Response $response, $halaman, $sectionKey)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        if (!isset($this->resourceMap[$halaman]) || ($this->resourceMap[$halaman]['form_type'] ?? null) !== 'section') {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $config = $this->resourceMap[$halaman];
        $admin  = $_SESSION['admin'];

        if (!$this->authorizeRole($config, $admin)) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        $sectionDef = $this->findSectionDef($config, $sectionKey);
        if ($sectionDef === null) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        if ($sectionDef['locked']) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [
                'message' => 'Section ini menampilkan data dari "' . $sectionDef['lockedTarget'] . '" — edit lewat halaman itu, bukan di sini.',
            ], 'admin');
            return;
        }

        $resourceModel = new Resource();
        $row           = $resourceModel->findSection($config['page_slug'], $sectionKey);
        $fields        = $sectionDef['fields'] ?? [];

        $data = $this->buildSectionFormData($row, $fields, $resourceModel);

        $response->view('admin.forms.form-section', [
            'currentPage'      => $halaman,
            'admin'            => $admin,
            'resourceKey'      => $halaman,
            'pageLabel'        => $config['label'],
            'breadcrumbParent' => 'Data Konten',
            'sectionKey'       => $sectionKey,
            'sectionLabel'     => $sectionDef['label'],
            'isEdit'           => true,
            'fields'           => $fields,
            'data'             => $data,
        ], 'admin');
    }

    private const SECTION_RESERVED_COLUMNS = ['title', 'subtitle', 'description'];

    private function buildSectionFormData(?array $row, array $fields, Resource $resourceModel): array
    {
        $metaDecoded = [];
        if (!empty($row['meta'])) {
            $decoded = json_decode((string) $row['meta'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $metaDecoded = $decoded;
            }
        }

        $sectionId = $row['id'] ?? null;

        $data = [
            'title'       => $row['title'] ?? '',
            'subtitle'    => $row['subtitle'] ?? '',
            'description' => $row['description'] ?? '',
            'image_url'   => null,
        ];

        foreach ($fields as $field) {
            $key = $field['key'];

            if (in_array($key, self::SECTION_RESERVED_COLUMNS, true)) {
                continue;
            }

            if ($field['type'] === 'image') {
                $data['image_url'] = $sectionId !== null
                    ? $resourceModel->getCoverUrl('section', (int) $sectionId)
                    : null;
                continue;
            }

            if ($field['type'] === 'repeater') {
                $data[$key] = $metaDecoded[$key] ?? [];
                continue;
            }

            $data[$key] = $metaDecoded[$key] ?? '';
        }

        return $data;
    }

    public function sectionSave(Request $request, Response $response, $halaman)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        if (!isset($this->resourceMap[$halaman]) || ($this->resourceMap[$halaman]['form_type'] ?? null) !== 'section') {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $config = $this->resourceMap[$halaman];
        $admin  = $_SESSION['admin'];

        if (!$this->authorizeRole($config, $admin)) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        $sectionKey = (string) $request->input('section_key', '');
        $sectionDef = $this->findSectionDef($config, $sectionKey);

        if ($sectionDef === null || !empty($sectionDef['locked'])) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        $fields        = $sectionDef['fields'] ?? [];
        $resourceModel = new Resource();
        $existing      = $resourceModel->findSection($config['page_slug'], $sectionKey);
        $hasImageField = false;

        $data = [
            'title'       => trim((string) $request->input('title', '')),
            'subtitle'    => (string) $request->input('subtitle', ''),
            'description' => (string) $request->input('description', ''),
        ];

        $metaData = [];
        foreach ($fields as $field) {
            $key = $field['key'];

            if (in_array($key, self::SECTION_RESERVED_COLUMNS, true)) {
                continue;
            }

            if ($field['type'] === 'image') {
                $hasImageField = true;
                continue;
            }

            if ($field['type'] === 'repeater') {
                $metaData[$key] = $this->extractRepeaterRows($request, $field);
                continue;
            }

            $metaData[$key] = (string) $request->input($key, '');
        }

        $data['meta'] = !empty($metaData)
            ? json_encode($metaData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : null;

        if ($existing) {
            $sectionId = (int) $existing['id'];
            $saved     = $resourceModel->update('sections', $sectionId, $data);
        } else {
            // Ambil ID page berdasarkan slug (home / tentang-kami / destinasi-produk)
            $pageId = $resourceModel->getPageIdBySlug($config['page_slug']);

            $insertData = array_merge($data, [
                'page_id'     => $pageId,
                'section_key' => $sectionKey,
            ]);

            $sectionId = $resourceModel->create('sections', $insertData);
            $saved     = $sectionId !== false;
        }

        if (!$saved) {
            if (function_exists('set_flash')) {
                set_flash('error', 'Gagal menyimpan section ke database.');
            }
            header("location: " . url('admin/' . $halaman . '/list'));
            exit;
        }

        // Upload Gambar Section jika ada
        if ($hasImageField && $sectionId) {
            try {
                $this->handleSectionImageUpload($resourceModel, (int) $sectionId, $admin);
            } catch (\RuntimeException $e) {
                if (function_exists('set_flash')) {
                    set_flash('warning', 'Konten "' . $sectionDef['label'] . '" tersimpan, tapi gambar gagal diupload: ' . $e->getMessage());
                }
                header("location: " . url('admin/' . $halaman . '/list'));
                exit;
            }
        }

        if (function_exists('set_flash')) {
            set_flash('success', 'Konten "' . $sectionDef['label'] . '" berhasil diperbarui.');
        }

        header("location: " . url('admin/' . $halaman . '/list'));
        exit;
    }

    private function extractRepeaterRows(Request $request, array $field): array
    {
        $rows     = $request->input($field['key'], []);
        $itemKeys = array_column($field['item_fields'], 'key');
        $clean    = [];

        if (!is_array($rows)) {
            return $clean;
        }

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $item = [];
            foreach ($itemKeys as $itemKey) {
                $item[$itemKey] = trim((string) ($row[$itemKey] ?? ''));
            }

            if (implode('', $item) === '') {
                continue;
            }

            $clean[] = $item;
        }

        return $clean;
    }

    private function handleSectionImageUpload(Resource $resourceModel, int $sectionId, array $admin): void
    {
        if (empty($_FILES['image']['name'])) {
            return;
        }

        $uploader = new ImageUploader(
            uploadDir: getUploadPath('sections'),
            urlPrefix: '/uploads/sections'
        );

        $fileMeta = $uploader->upload($_FILES['image']);
        if ($fileMeta !== null) {
            $mediaId = $resourceModel->createMedia($fileMeta, $admin['id'] ?? null);
            $resourceModel->replaceCover('section', $sectionId, $mediaId, 'cover');
        }
    }

    private function findSectionDef(array $config, string $sectionKey): ?array
    {
        foreach ($config['sections'] as $sectionDef) {
            if ($sectionDef['key'] === $sectionKey) {
                return $sectionDef;
            }
        }

        return null;
    }

    // =========================================================
    //  CREATE
    // =========================================================

    public function resourceCreate(Request $request, Response $response, $halaman)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        if (!isset($this->resourceMap[$halaman]) || empty($this->resourceMap[$halaman]['form_type'])) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $config = $this->resourceMap[$halaman];
        $admin  = $_SESSION['admin'];

        if (!$this->authorizeRole($config, $admin)) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        $emptyItem = ['id' => null];

        $resourceModel = new Resource();
        $data          = $this->buildFormData($emptyItem, $config, $resourceModel);

        $this->renderForm($response, $config, $halaman, $admin, $data, false);
    }

    // =========================================================
    //  SAVE (create + update)
    // =========================================================

    public function resourceSave(Request $request, Response $response, $halaman)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        if ($halaman === 'administrator') {
            $authController = new AuthController();
            return $authController->registerProcess($request, $response);
        }

        if (!isset($this->resourceMap[$halaman]) || empty($this->resourceMap[$halaman]['form_type'])) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $config    = $this->resourceMap[$halaman];
        $admin     = $_SESSION['admin'];

        if (!$this->authorizeRole($config, $admin)) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        $tableName = $config['table'] ?? 'sections';
        $resourceModel = new Resource();

        $rawId  = $request->input('id');
        $isEdit = !empty($rawId);

        if ($isEdit) {
            $existing = $resourceModel->find($tableName, (int) $rawId);
            if (!$existing) {
                $response->setStatusCode(404);
                $response->view('errors.admin.under-development', [], 'admin');
                return;
            }
        }

        $validationError = $this->validateResourceInput($request, $config, $isEdit);
        if ($validationError !== null) {
            $this->flashAndRedirectBack($halaman, $isEdit ? (int) $rawId : null, 'error', $validationError);
            return;
        }

        $columnData = $this->extractColumnData($request, $config);

        if (empty($columnData)) {
            $this->flashAndRedirectBack($halaman, $isEdit ? (int) $rawId : null, 'error', 'Tidak ada data input yang valid untuk disimpan.');
            return;
        }

        if ($isEdit) {
            $entityId = (int) $rawId;
            $saved    = $resourceModel->update($tableName, $entityId, $columnData);
        } else {
            $entityId = $resourceModel->create($tableName, $columnData);
            $saved    = $entityId !== false;
        }

        if (!$saved) {
            $this->flashAndRedirectBack($halaman, $isEdit ? (int) $rawId : null, 'error', 'Gagal menyimpan data ke database. Coba lagi.');
            return;
        }

        if (!empty($config['entity_type'])) {
            try {
                $this->handleImageUploads($request, $resourceModel, $config['entity_type'], (int) $entityId, $admin, $tableName);
            } catch (\RuntimeException $e) {
                $this->flashAndRedirectBack(
                    $halaman,
                    (int) $entityId,
                    'warning',
                    ucfirst($config['label']) . ' tersimpan, tapi gambar gagal diupload: ' . $e->getMessage()
                );
                return;
            }
        }

        if (function_exists('set_flash')) {
            set_flash('success', $isEdit
                ? ucfirst($config['label']) . ' berhasil diperbarui.'
                : ucfirst($config['label']) . ' berhasil ditambahkan.');
        }

        return handle_result($response, $saved, "admin/$halaman/edit/$entityId", $saved ? 'Berhasil' : 'Gagal');
    }

    // =========================================================
    //  DELETE
    // =========================================================

    public function resourceDelete(Request $request, Response $response, $halaman, $id)
    {
        if (!isset($_SESSION['admin'])) {
            header("location: " . url('/login'));
            exit;
        }

        if (!isset($this->resourceMap[$halaman])) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $config    = $this->resourceMap[$halaman];
        $admin     = $_SESSION['admin'];

        if (!$this->authorizeRole($config, $admin)) {
            $response->setStatusCode(403);
            $response->view('errors.admin.forbidden', [], 'admin');
            return;
        }

        $tableName     = $config['table'] ?? 'sections';
        $resourceModel = new Resource();
        $item          = $resourceModel->find($tableName, (int) $id);

        if (!$item) {
            $response->setStatusCode(404);
            $response->view('errors.admin.under-development', [], 'admin');
            return;
        }

        $resourceModel->deleteWithMedia(
            $tableName,
            (int) $id,
            $config['entity_type'] ?? null
        );

        $redirectTarget = ($halaman === 'program-kkn' || $halaman === 'mahasiswa-kkn') ? 'kkn' : $halaman;
        header("location: " . url('admin/' . $redirectTarget . '/list'));
        exit;
    }

    // =========================================================
    //  HELPERS
    // =========================================================

    private function authorizeRole(array $config, array $admin): bool
    {
        if (empty($config['roles'])) {
            return true;
        }

        return in_array($admin['role'] ?? null, $config['roles'], true);
    }

    private function renderForm(
        Response $response,
        array $config,
        string $halaman,
        array $admin,
        array $data,
        bool $isEdit
    ): void {
        $viewPath = 'admin.forms.form-' . $config['form_type'];
        $meta     = $config['form_meta'] ?? [];

        $viewData = [
            'currentPage'      => $halaman,
            'admin'            => $admin,
            'resourceKey'      => $halaman,
            'pageLabel'        => $config['label'],
            'breadcrumbParent' => 'Data Konten',
            'isEdit'           => $isEdit,
            'data'             => $data,
        ];

        if ($config['form_type'] === 'lengkap') {
            $viewData['secondaryField'] = isset($meta['secondary_column']) ? [
                'key'         => $meta['secondary_column'],
                'label'       => $meta['secondary_label'] ?? '',
                'placeholder' => $meta['secondary_placeholder'] ?? '',
            ] : null;
            $viewData['statusType']   = $meta['status_type'] ?? 'published_draft';
            $viewData['showFeatured'] = $meta['show_featured'] ?? false;
        }

        if ($config['form_type'] === 'profil') {
            $viewData['primaryLabel']    = $meta['primary_label'] ?? 'Nama';
            $viewData['showPhoto']       = $meta['show_photo'] ?? false;
            $viewData['photoLabel']      = $meta['photo_label'] ?? 'Foto';
            $viewData['showDescription'] = $meta['show_description'] ?? false;
            $viewData['showSortOrder']   = $meta['show_sort_order'] ?? false;
            $viewData['extraFields']     = $meta['extra_fields'] ?? [];
        }

        $response->view($viewPath, $viewData, 'admin');
    }

    private function buildFormData(array $item, array $config, Resource $resourceModel): array
    {
        return match ($config['form_type']) {
            'lengkap' => $this->mapLengkapData($item, $config, $resourceModel),
            'ringkas' => $this->mapRingkasData($item, $config, $resourceModel),
            'profil'  => $this->mapProfilData($item, $config, $resourceModel),
            default   => $item,
        };
    }

    private function mapLengkapData(array $item, array $config, Resource $resourceModel): array
    {
        $meta = $config['form_meta'] ?? [];
        $id   = $item['id'] ?? null;

        $coverUrl = null;
        $gallery  = [];

        if ($id !== null && !empty($config['entity_type'])) {
            $coverUrl = $resourceModel->getCoverUrl($config['entity_type'], (int) $id);
            $gallery  = $resourceModel->getGalleryUrls($config['entity_type'], (int) $id);
        }

        $statusType = $meta['status_type'] ?? 'published_draft';
        $status     = $item['status']
            ?? $item['is_active']
            ?? ($statusType === 'active_inactive' ? 1 : 'draft');

        $content = $item['content'] ?? [];
        if (is_string($content)) {
            $decoded = json_decode($content, true);
            $content = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
        }

        return [
            'id'          => $id,
            'title'       => $item['title'] ?? '',
            'slug'        => $item['slug'] ?? '',
            'secondary'   => isset($meta['secondary_column']) ? ($item[$meta['secondary_column']] ?? '') : '',
            'excerpt'     => $item['excerpt'] ?? '',
            'content'     => $content,
            'status'      => $status,
            'sort_order'  => $item['sort_order'] ?? '',
            'is_featured' => (bool) ($item['is_featured'] ?? false),
            'cover_url'   => $coverUrl,
            'gallery'     => $gallery,
        ];
    }

    private function mapRingkasData(array $item, array $config, Resource $resourceModel): array
    {
        $id       = $item['id'] ?? null;
        $coverUrl = null;

        if ($id !== null && !empty($config['entity_type'])) {
            $coverUrl = $resourceModel->getCoverUrl($config['entity_type'], (int) $id);
        }

        return [
            'id'         => $id,
            'title'      => $item['title'] ?? '',
            'excerpt'    => $item['excerpt'] ?? '',
            'status'     => $item['is_active'] ?? 1,
            'sort_order' => $item['sort_order'] ?? '',
            'cover_url'  => $coverUrl,
        ];
    }

    private function mapProfilData(array $item, array $config, Resource $resourceModel): array
    {
        $meta = $config['form_meta'] ?? [];
        $id   = $item['id'] ?? null;

        $photoUrl = null;
        if (($meta['show_photo'] ?? false) && $id !== null && !empty($config['entity_type'])) {
            $photoUrl = $resourceModel->getCoverUrl($config['entity_type'], (int) $id);
        }

        $data = [
            'id'          => $id,
            'name'        => $item['name'] ?? '',
            'description' => $item['description'] ?? '',
            'status'      => $item['is_active'] ?? 1,
            'sort_order'  => $item['sort_order'] ?? '',
            'photo_url'   => $photoUrl,
        ];

        foreach ($meta['extra_fields'] ?? [] as $field) {
            $data[$field['key']] = $field['type'] === 'password'
                ? ''
                : ($item[$field['key']] ?? '');
        }

        return $data;
    }

    private function validateResourceInput(Request $request, array $config, bool $isEdit = false): ?string
    {
        $formType = $config['form_type'];

        if ($formType === 'lengkap' || $formType === 'ringkas') {
            if (trim((string) $request->input('title', '')) === '') {
                return 'Judul wajib diisi.';
            }
        }

        if ($formType === 'profil') {
            if (trim((string) $request->input('name', '')) === '') {
                return 'Nama wajib diisi.';
            }

            // Password kosong itu VALID saat EDIT (artinya "jangan diubah"),
            // tapi TIDAK VALID saat CREATE — kolom password_hash NOT NULL,
            // jadi kalau lolos ke database bakal gagal INSERT diam-diam.
            if (!$isEdit) {
                foreach ($config['form_meta']['extra_fields'] ?? [] as $field) {
                    if ($field['type'] === 'password' && trim((string) $request->input($field['key'], '')) === '') {
                        return ucfirst($field['label']) . ' wajib diisi saat membuat baru.';
                    }
                }
            }
        }

        return null;
    }

    private function extractColumnData(Request $request, array $config): array
    {
        $formType = $config['form_type'];
        $meta     = $config['form_meta'] ?? [];
        $data     = [];

        if ($formType === 'lengkap') {
            $title = trim((string) $request->input('title', ''));
            $slug  = trim((string) $request->input('slug', ''));

            $data['title']   = $title;
            $data['slug']    = $this->slugify($slug !== '' ? $slug : $title);
            $data['excerpt'] = (string) $request->input('excerpt', '');
            $data['content'] = (string) $request->input('content', '[]');

            $data['sort_order'] = (int) $request->input('sort_order', 0);

            if (!empty($meta['secondary_column'])) {
                $data[$meta['secondary_column']] = (string) $request->input($meta['secondary_column'], '');
            }

            if (($meta['status_type'] ?? 'published_draft') === 'active_inactive') {
                $data['is_active'] = $request->input('status') ? 1 : 0;
            } else {
                $status         = $request->input('status', 'draft');
                $data['status'] = in_array($status, ['published', 'draft'], true) ? $status : 'draft';
            }

            if (!empty($meta['show_featured'])) {
                $data['is_featured'] = $request->input('is_featured') ? 1 : 0;
            }
        }

        if ($formType === 'ringkas') {
            $data['title']      = trim((string) $request->input('title', ''));
            $data['excerpt']    = (string) $request->input('excerpt', '');
            $data['is_active']  = $request->input('status') ? 1 : 0;
            $data['sort_order'] = (int) $request->input('sort_order', 0);
        }

        if ($formType === 'profil') {
            $data['name']      = trim((string) $request->input('name', ''));
            $data['is_active'] = $request->input('status') ? 1 : 0;

            if (!empty($meta['show_description'])) {
                $data['description'] = (string) $request->input('description', '');
            }
            if (!empty($meta['show_sort_order'])) {
                $data['sort_order'] = (int) $request->input('sort_order', 0);
            }

            foreach ($meta['extra_fields'] ?? [] as $field) {
                $key = $field['key'];

                if ($field['type'] === 'password') {
                    $plain = (string) $request->input($key, '');
                    if ($plain !== '') {
                        $data['password_hash'] = password_hash($plain, PASSWORD_BCRYPT);
                    }
                    continue;
                }

                $data[$key] = (string) $request->input($key, '');
            }
        }

        return $data;
    }

    private function slugify(string $text): string
    {
        $text = trim($text);
        $ascii = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($ascii !== false && $ascii !== '') {
            $text = $ascii;
        }
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        $text = trim($text, '-');

        return $text !== '' ? $text : 'item-' . substr(md5(uniqid('', true)), 0, 8);
    }

    private function handleImageUploads(Request $request, Resource $resourceModel, string $entityType, int $entityId, array $admin, string $tableName = ''): void
    {
        // Tentukan nama subfolder berdasarkan nama tabel (jika ada) atau entityType
        $subFolder = !empty($tableName) ? $tableName : $entityType;

        $uploader = new ImageUploader(
            uploadDir: getUploadPath($subFolder),
            urlPrefix: '/uploads/' . $subFolder
        );

        $uploadedBy = $admin['id'] ?? null;

        if (!empty($_FILES['cover_image']['name'])) {
            $fileMeta = $uploader->upload($_FILES['cover_image']);
            if ($fileMeta !== null) {
                $mediaId = $resourceModel->createMedia($fileMeta, $uploadedBy);
                $resourceModel->replaceCover($entityType, $entityId, $mediaId, 'cover');
            }
        }

        if (!empty($_FILES['gallery_images']['name'][0])) {
            $galleryMetas = $uploader->uploadMultiple($_FILES['gallery_images']);
            $sortOrder    = 0;
            foreach ($galleryMetas as $fileMeta) {
                $mediaId = $resourceModel->createMedia($fileMeta, $uploadedBy);
                $resourceModel->attachMedia($entityType, $entityId, $mediaId, 'gallery', $sortOrder++);
            }
        }
    }

    private function flashAndRedirectBack(string $halaman, ?int $id, string $type, string $message): void
    {
        if (function_exists('set_flash')) {
            set_flash($type, $message);
        }

        $target = $id !== null
            ? 'admin/' . $halaman . '/' . $id . '/edit'
            : 'admin/' . $halaman . '/create';

        header("location: " . url($target));
        exit;
    }
    /**
     * AJAX Endpoint untuk mengubah status toggle (is_active / is_featured / status)
     */
    public function toggleStatus(Request $request, Response $response, $halaman, $id)
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['admin'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthenticated']);
            exit;
        }

        if (!isset($this->resourceMap[$halaman])) {
            echo json_encode(['success' => false, 'message' => 'Resource tidak ditemukan']);
            exit;
        }

        $config    = $this->resourceMap[$halaman];
        $admin     = $_SESSION['admin'];
        $tableName = $config['table'] ?? '';

        if (!$this->authorizeRole($config, $admin)) {
            echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
            exit;
        }

        // Ambil field yang ingin di-toggle dari request (default: is_featured atau is_active)
        $field = trim((string) $request->input('field', ''));

        // Validasi field agar tidak bisa mengacak-acak kolom lain
        $allowedFields = ['is_featured', 'is_active', 'status'];
        if (!in_array($field, $allowedFields, true)) {
            echo json_encode(['success' => false, 'message' => 'Kolom toggle tidak valid']);
            exit;
        }

        $resourceModel = new Resource();
        $item          = $resourceModel->find($tableName, (int) $id);

        if (!$item) {
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
            exit;
        }

        // Tentukan nilai baru (Toggle 1 -> 0 atau 0 -> 1)
        $currentValue = $item[$field] ?? 0;

        if ($field === 'status') {
            // Jika kolomnya bertipe enum 'published'/'draft'
            $newValue = ($currentValue === 'published') ? 'draft' : 'published';
        } else {
            // Jika kolomnya boolean/tinyint 1 / 0
            $newValue = $currentValue ? 0 : 1;
        }

        // Update di database
        $updated = $resourceModel->update($tableName, (int) $id, [
            $field => $newValue
        ]);

        if ($updated) {
            echo json_encode([
                'success'   => true,
                'message'   => 'Status berhasil diperbarui',
                'new_value' => $newValue
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui database']);
        }
        exit;
    }
    public function reorderItem(Request $request, Response $response, $halaman, $id)
    {
        if (!isset($this->resourceMap[$halaman])) {
            $response->setStatusCode(400);
            $response->json(['success' => false, 'message' => 'Konfigurasi resource tidak ditemukan']);
            return;
        }

        $config = $this->resourceMap[$halaman];
        $direction = $request->input('direction');

        if (!in_array($direction, ['up', 'down'], true)) {
            $response->setStatusCode(400);
            $response->json(['success' => false, 'message' => 'Arah tidak valid']);
            return;
        }

        $resourceModel = new Resource();
        $swapped = $resourceModel->moveSortOrder($config['table'], (int) $id, $direction);

        if ($swapped) {
            $response->json(['success' => true, 'message' => 'Urutan berhasil diperbarui']);
            return;
        }

        $response->json(['success' => false, 'message' => 'Sudah berada di batas urutan']);
    }
    public function approveAdmin(Request $request, Response $response, $id)
    {
        if (!isset($_SESSION['admin']) || ($_SESSION['admin']['role'] ?? '') !== 'Kepala Admin') {
            if (function_exists('set_flash')) {
                set_flash('error', 'Akses ditolak.');
            }
            header("location: " . url('admin/administrator/list'));
            exit;
        }

        $status = $request->input('status'); // 'approved' atau 'rejected'
        if (!in_array($status, ['approved', 'rejected'], true)) {
            if (function_exists('set_flash')) {
                set_flash('error', 'Status tidak valid.');
            }
            header("location: " . url('admin/administrator/list'));
            exit;
        }

        $resourceModel = new Resource();
        $updated = $resourceModel->update('admins', (int) $id, [
            'status' => $status
        ]);

        if (function_exists('set_flash')) {
            if ($updated) {
                $label = $status === 'approved' ? 'disetujui' : 'ditolak';
                set_flash('success', "Status pendaftaran administrator berhasil {$label}.");
            } else {
                set_flash('error', "Gagal memperbarui status administrator.");
            }
        }

        header("location: " . url('admin/administrator/list'));
        exit;
    }
    /**
     * Menampilkan halaman edit Pengaturan Website
     */
    public function showSettings(Request $request, Response $response)
    {
        // Restriksi hanya untuk Kepala Admin
        if (!isset($_SESSION['admin']) || ($_SESSION['admin']['role'] ?? '') !== 'Kepala Admin') {
            if (function_exists('set_flash')) {
                set_flash('error', 'Akses ditolak.');
            }
            header("location: " . url('admin/dashboard'));
            exit;
        }

        $resourceModel = new \App\Models\Resource();

        // Ambil semua data settings dari database (format: ['setting_key' => 'setting_value'])
        $settings = $resourceModel->getAllSettings();

        // Render view form edit dengan membawa data settings
        $response->view('admin.settings-edit', [
            'pageTitle' => 'Edit Pengaturan Website',
            'settings'  => $settings,
            'config'    => $this->resourceMap['settings'] ?? []
        ], 'admin');
    }
    /**
     * Helper untuk menangani proses upload file/gambar
     * 
     * @param array  $fileData  Data dari $_FILES['nama_input']
     * @param string $folder    Sub-folder tujuan di dalam /uploads/
     * @return array
     */
    private function handleFileUpload(array $fileData, string $folder = 'general'): array
    {
        // 1. Cek error bawaan upload PHP
        if (!isset($fileData['tmp_name']) || $fileData['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Gagal mengunggah file.'];
        }

        // 2. Validasi Ekstensi File yang Diizinkan
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $fileExtension = strtolower(pathinfo($fileData['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions, true)) {
            return ['success' => false, 'error' => 'Format file tidak didukung. Gunakan JPG, PNG, WebP, atau SVG.'];
        }

        // 3. Validasi Ukuran File (Maksimal 5MB)
        $maxSize = 5 * 1024 * 1024; // 5 MB
        if ($fileData['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Ukuran file terlalu besar. Maksimal 5MB.'];
        }

        // 4. Siapkan Folder Tujuan (misal: /public/uploads/settings/)
        $uploadDir = __DIR__ . '/../../public/uploads/' . trim($folder, '/') . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 5. Generate Nama File Unik agar Tidak Menghentikan/Menimpa File Lain
        $newFileName = time() . '_' . bin2hex(random_bytes(4)) . '.' . $fileExtension;
        $targetPath  = $uploadDir . $newFileName;

        // 6. Pindahkan File dari Temp ke Folder Upload
        if (move_uploaded_file($fileData['tmp_name'], $targetPath)) {
            // Return relative path untuk disimpan ke database
            return [
                'success'  => true,
                'filepath' => '/uploads/' . trim($folder, '/') . '/' . $newFileName
            ];
        }

        return ['success' => false, 'error' => 'Gagal menyimpan file ke server.'];
    }
    /**
     * Menyimpan/memperbarui seluruh data settings (Bulk Update)
     */
    public function saveSettings(Request $request, Response $response)
    {
        if (!isset($_SESSION['admin']) || ($_SESSION['admin']['role'] ?? '') !== 'Kepala Admin') {
            if (function_exists('set_flash')) {
                set_flash('error', 'Akses ditolak.');
            }
            header("location: " . url('admin/settings/edit'));
            exit;
        }

        $resourceModel = new \App\Models\Resource();
        $inputs        = $request->all();

        // Handling khusus Upload Logo jika ada
        if (isset($_FILES['village_logo']) && $_FILES['village_logo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleFileUpload($_FILES['village_logo'], 'settings');
            if ($uploadResult['success']) {
                $inputs['village_logo'] = $uploadResult['filepath'];
            }
        }

        // Loop semua input dan simpan ke database
        foreach ($inputs as $key => $value) {
            if (in_array($key, ['_token', 'id'], true)) {
                continue;
            }

            $resourceModel->updateSettingByKey($key, is_array($value) ? json_encode($value) : (string) $value);
        }

        if (function_exists('set_flash')) {
            set_flash('success', 'Pengaturan website berhasil diperbarui!');
        }

        header("location: " . url('admin/settings/edit'));
        exit;
    }
}
