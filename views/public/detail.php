<?php

/**
 * View: detail (reusable untuk Destinasi & Produk)
 * Pure content fragment — no <html>, no layout, sama seperti
 * home.php/about.php/produk.php.
 *
 * Kenapa 1 file untuk 2 resource: struktur datanya sama persis
 * (title, secondary field, excerpt, content, cover, gallery) — cuma
 * label & 1 kolom yang beda (lokasi vs info harga), sama seperti
 * form-lengkap.php di sisi admin yang juga dipakai bersama untuk
 * Destinasi/Produk/Program KKN.
 *
 * Expected variables (nanti diisi controller dari database):
 *   $pageType        string  'destinasi' | 'produk' — dipakai untuk
 *                             fallback label/href kalau tidak di-override manual.
 *   $eyebrowLabel    string  mis. 'Destinasi Wisata' | 'Produk Unggulan'
 *   $secondaryLabel  string  mis. 'Lokasi' | 'Info Harga'
 *   $backLabel       string  teks tombol kembali
 *   $backHref        string  href tombol kembali (halaman listing)
 *   $data            array   title, secondary, excerpt, content,
 *                            cover_url, gallery (array of image URLs)
 *   $relatedLabel    string  mis. 'Destinasi Lainnya' | 'Produk Lainnya'
 *   $relatedItems    array   list item terkait, tiap item:
 *                            ['title', 'excerpt', 'href', 'image_label']
 */

if (!isset($pageType)) {
    $pageType = 'destinasi';
}

// Default label/href per pageType — controller boleh override manual
// lewat variabel di atas kalau perlu beda dari default ini.
$defaultsByType = [
    'destinasi' => [
        'eyebrowLabel'   => 'Destinasi Wisata',
        'secondaryLabel' => 'Lokasi',
        'backLabel'      => 'Kembali ke Destinasi & Produk',
        'backHref'       => 'produk.html',
        'relatedLabel'   => 'Destinasi Lainnya',
    ],
    'produk' => [
        'eyebrowLabel'   => 'Produk Unggulan',
        'secondaryLabel' => 'Info Harga',
        'backLabel'      => 'Kembali ke Destinasi & Produk',
        'backHref'       => 'produk.html',
        'relatedLabel'   => 'Produk Lainnya',
    ],
];
$typeDefaults = $defaultsByType[$pageType] ?? $defaultsByType['destinasi'];

if (!isset($eyebrowLabel)) {
    $eyebrowLabel = $typeDefaults['eyebrowLabel'];
}
if (!isset($secondaryLabel)) {
    $secondaryLabel = $typeDefaults['secondaryLabel'];
}
if (!isset($backLabel)) {
    $backLabel = $typeDefaults['backLabel'];
}
if (!isset($backHref)) {
    $backHref = $typeDefaults['backHref'];
}
if (!isset($relatedLabel)) {
    $relatedLabel = $typeDefaults['relatedLabel'];
}

if (isset($item) && !isset($data)) {
    $data = $item;
}

// Placeholder default — dipakai untuk isi field yang TIDAK dikirim controller.
// Sengaja pakai array_merge, bukan "if (!isset($data))" all-or-nothing —
// supaya kalau controller cuma kirim sebagian field (mis. baru 'title' saja,
// belum 'excerpt'/'content'), sisanya tetap ke-isi placeholder yang masuk akal
// alih-alih memicu "Undefined array key".


// $data dari controller (kalau ada) menimpa placeholder di atas field-per-field —
// bukan gantiin semuanya. Jadi walau controller cuma kirim 'title' saja,
// 'excerpt'/'content'/dst tetap ada isinya (placeholder), tidak undefined.

?>
<header class="hero">
    <div class="hero__bg" data-parallax="0.22">
        <div class="ph">
            <?php if (!empty($data['thumbnail_url'])): ?>
                <img src="<?= url(htmlspecialchars($data['thumbnail_url'])) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
            <?php else: ?>
                <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 16l5-5 4 4 5-6 4 5" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
                <span class="ph__label">Foto: <?= htmlspecialchars($data['title']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero__overlay"></div>
    <div class="container hero__content">
        <span class="eyebrow"><?= htmlspecialchars($eyebrowLabel) ?></span>
        <h1 data-reveal="fade"><?= htmlspecialchars($data['title']) ?></h1>
        <?php if (!empty($data['secondary'])): ?>
            <p data-reveal="fade"><?= htmlspecialchars($data['secondary']) ?></p>
        <?php endif; ?>
    </div>
</header>

<!-- ============ KEMBALI ============ -->
<section class="section--tight">
    <div class="container">
        <a href="<?= htmlspecialchars($backHref) ?>" class="link-arrow" data-reveal="fade">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M11 6l-6 6 6 6" />
            </svg>
            <?= htmlspecialchars($backLabel) ?>
        </a>
    </div>
</section>

<!-- ============ GALERI & DESKRIPSI ============ -->
<section class="section section--surface">
    <div class="container grid-2">
        <div data-reveal="left">
            <?php if (!empty($data['gallery'])): ?>
                <div class="detail-gallery">
                    <?php foreach ($data['gallery'] as $g): ?>
                        <div class="detail-gallery__item ph">
                            <img src="<?= url(htmlspecialchars($g)) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="intro__body" data-reveal="right">
            <h2>Tentang <?= $pageType === 'produk' ? 'Produk' : 'Destinasi' ?> Ini</h2>

            <?php if (!empty($data['excerpt'])): ?>
                <p><?= htmlspecialchars($data['excerpt']) ?></p>
            <?php endif; ?>

            <?php if (!empty($data['secondary'])): ?>
                <p style="color: var(--clr-text-light); font-size: var(--fs-small);">
                    <strong><?= htmlspecialchars($secondaryLabel) ?>:</strong> <?= htmlspecialchars($data['secondary']) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ============ KONTEN LENGKAP ============ -->
<?php
    // 'content' sekarang disimpan sebagai JSON array of blocks di database,
    // BUKAN HTML mentah dari rich text editor lagi. Tiap block cuma tipe
    // yang sudah didefinisikan (paragraph/heading/image/quote/list/divider),
    // jadi tidak ada risiko HTML/script sembarangan ke-inject seperti
    // pendekatan sebelumnya — sekaligus tiap tipe block dapat class sendiri
    // (content-block--paragraph, --heading, dst) yang bisa distyling terpisah.
    //
    // Format field 'content' yang diharapkan (array PHP ATAU string JSON,
    // dua-duanya didukung):
    //   [
    //     ['type' => 'paragraph', 'text' => '...'],
    //     ['type' => 'heading',   'level' => 2, 'text' => '...'],
    //     ['type' => 'image',     'url' => '...', 'caption' => '...', 'alt' => '...'],
    //     ['type' => 'quote',     'text' => '...', 'cite' => '...'],
    //     ['type' => 'list',      'style' => 'unordered'|'ordered', 'items' => ['...', '...']],
    //     ['type' => 'divider'],
    //   ]

/**
 * Saring HTML sampai cuma tersisa <strong>/<em>/<u>/<a href> — dipakai
 * KHUSUS untuk field 'text' di block paragraph & quote, karena field itu
 * diisi lewat mini rich-text editor (lihat block-editor.js) yang boleh
 * hasilkan tag-tag itu. Ini pertahanan SESUNGGUHNYA (server-side) —
 * sanitizer versi JS di block-editor.js cuma untuk UX/konsistensi, bukan
 * jaminan keamanan, karena orang bisa saja mem-bypass JS dan kirim POST
 * langsung. Semua tag & atribut di luar whitelist ini dibuang total.
 */
function sanitizeInlineHtml(string $html): string
{
    $html = strip_tags($html, '<strong><b><em><i><u><a><br>');

    // Normalisasi <b>/<i> -> <strong>/<em> biar konsisten dgn CSS content-block.
    $html = preg_replace(
        ['/<b(\s|>)/i', '#</b>#i', '/<i(\s|>)/i', '#</i>#i'],
        ['<strong$1', '</strong>', '<em$1', '</em>'],
        $html
    );

    // <strong>/<em>/<u> tidak butuh atribut apapun — buang semuanya.
    $html = preg_replace('/<(strong|em|u)\s+[^>]*>/i', '<$1>', $html);

    // <a> cuma boleh punya href, dan hanya skema http/https/mailto.
    $html = preg_replace_callback('/<a\s+([^>]*)>/i', function ($m) {
        $href = '#';
        if (preg_match('/href\s*=\s*"([^"]*)"/i', $m[1], $hrefMatch)) {
            $candidate = trim($hrefMatch[1]);
            $scheme    = parse_url($candidate, PHP_URL_SCHEME);
            if ($candidate !== '' && ($scheme === null || in_array(strtolower($scheme), ['http', 'https', 'mailto'], true))) {
                $href = $candidate;
            }
        }
        return '<a href="' . htmlspecialchars($href, ENT_QUOTES) . '" target="_blank" rel="noopener noreferrer">';
    }, $html);

    return $html;
}

function renderContentBlock(array $block): string
{
    $type = $block['type'] ?? 'paragraph';

    switch ($type) {
        case 'heading':
            // Heading sengaja TETAP teks polos (htmlspecialchars penuh) — tidak
            // dikasih rich text di editor, jadi tidak perlu izinkan tag apapun.
            $level = in_array($block['level'] ?? 2, [2, 3, 4], true) ? (int) $block['level'] : 2;
            return sprintf(
                '<h%1$d class="content-block content-block--heading">%2$s</h%1$d>',
                $level,
                htmlspecialchars($block['text'] ?? '')
            );

        case 'image':
            $caption = !empty($block['caption'])
                ? '<figcaption class="content-block__caption">' . htmlspecialchars($block['caption']) . '</figcaption>'
                : '';
            return '<figure class="content-block content-block--image">'
                . '<img src="' . htmlspecialchars($block['url'] ?? '') . '" alt="' . htmlspecialchars($block['alt'] ?? '') . '" />'
                . $caption
                . '</figure>';

        case 'quote':
            $cite = !empty($block['cite'])
                ? '<cite class="content-block__cite">' . htmlspecialchars($block['cite']) . '</cite>'
                : '';
            return '<blockquote class="content-block content-block--quote">'
                . '<p>' . sanitizeInlineHtml($block['text'] ?? '') . '</p>'
                . $cite
                . '</blockquote>';

        case 'list':
            $tag   = ($block['style'] ?? 'unordered') === 'ordered' ? 'ol' : 'ul';
            $items = '';
            foreach ($block['items'] ?? [] as $listItem) {
                $items .= '<li>' . htmlspecialchars((string) $listItem) . '</li>';
            }
            return "<{$tag} class=\"content-block content-block--list\">{$items}</{$tag}>";

        case 'divider':
            return '<hr class="content-block content-block--divider" />';

        case 'paragraph':
        default:
            // Field 'text' di sini boleh mengandung <strong>/<em>/<u>/<a> dari
            // mini rich-text editor — makanya pakai sanitizeInlineHtml(), BUKAN
            // htmlspecialchars() penuh seperti block type lain.
            return '<p class="content-block content-block--paragraph">' . sanitizeInlineHtml($block['text'] ?? '') . '</p>';
    }
}

// Terima dua bentuk: array PHP (kalau controller sudah json_decode duluan)
// ATAU string JSON mentah langsung dari kolom database.
if (is_array($data['content'] ?? null)) {
    $contentBlocks = $data['content'];
} elseif (is_string($data['content'] ?? null) && $data['content'] !== '') {
    $decoded       = json_decode($data['content'], true);
    $contentBlocks = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [];
} else {
    $contentBlocks = [];
}
?>
<?php if (!empty($contentBlocks)): ?>
    <section class="section section--surface">
        <div class="container">
            <div class="article-body" data-reveal="fade">
                <?php foreach ($contentBlocks as $block): ?>
                    <?php if (is_array($block)) echo renderContentBlock($block); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ============ TERKAIT ============ -->
<?php if (!empty($relatedItems)): ?>
    <section class="section section--alt">
        <div class="container">
            <div class="section__head" data-reveal="fade">
                <h2><?= htmlspecialchars($relatedLabel) ?></h2>
            </div>

            <div class="grid-3">
                <?php foreach ($relatedItems as $related): ?>
                    <div class="potensi-card" data-reveal="scale" data-stagger-group="related">
                        <div class="potensi-card__media">
                            <div class="ph">
                                <?php if (!empty($related['image_url'])): ?>
                                    <img src="<?= htmlspecialchars($related['image_url']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" />
                                <?php else: ?>
                                    <svg class="ph__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M3 16l5-5 4 4 5-6 4 5" />
                                        <circle cx="12" cy="12" r="10" />
                                    </svg>
                                    <span class="ph__label"><?= htmlspecialchars($related['image_label'] ?? '') ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <h3><a href="<?= htmlspecialchars($related['href']) ?>"><?= htmlspecialchars($related['title']) ?></a></h3>
                        <p><?= htmlspecialchars($related['excerpt']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>