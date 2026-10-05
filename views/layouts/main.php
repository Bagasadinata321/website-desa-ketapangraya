<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- SEO Meta Tags -->
    <title><?= htmlspecialchars($metaTitle ?? ('Desa Ketapang Raya' . (isset($title) && $title !== '' ? ' - ' . $title : ''))) ?></title>
    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? 'Website resmi informasi wisata, produk unggulan, dan potensi Desa Ketapang Raya.') ?>" />
    <meta name="keywords" content="Desa Ketapang Raya, Wisata Ketapang Raya, Ekowisata, Produk Desa" />
    <meta name="robots" content="index, follow" />

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= url($_SERVER['REQUEST_URI'] ?? '') ?>" />

    <!-- Open Graph / Social Media Meta Tags (WhatsApp, Facebook, LinkedIn) -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle ?? ($title ?? 'Desa Ketapang Raya')) ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($metaDescription ?? 'Portal Resmi Desa Ketapang Raya') ?>" />
    <meta property="og:image" content="<?= htmlspecialchars($metaImage ?? url('/assets/images/og-default.jpg')) ?>" />
    <meta property="og:url" content="<?= url($_SERVER['REQUEST_URI'] ?? '') ?>" />

    <!-- Stylesheets & Theme Variables -->
    <link rel="stylesheet" href="<?= noCache('/css/root.css'); ?>" />
    <link rel="stylesheet" href="<?= noCache('/css/root-earth.css'); ?>" />
    <link rel="stylesheet" href="<?= noCache('/css/root-sunset.css'); ?>" />
    <link rel="stylesheet" href="<?= noCache('/css/root-dark.css'); ?>" />
    <link rel="stylesheet" href="<?= noCache('/css/style.css'); ?>" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Anti-Flicker Theme Script (Di-load awal sebelum rendering body) -->
    <script>
        (function() {
            try {
                var saved = localStorage.getItem("theme");
                if (saved && saved !== "default") {
                    document.documentElement.setAttribute("data-theme", saved);
                }
            } catch (e) {}
        })();
    </script>
</head>

<body data-page="<?= $page ?>">
    <?= partial('navbar-kkn'); ?>

    <?= $content ?>

    <?= partial('footer-kkn'); ?>

    <div id="custom-alert" class="alert-overlay">
        <div class="alert-box">
            <div class="alert-icon" id="alert-icon">✔</div>
            <h3 id="alert-title">Success</h3>
            <p id="alert-message">Pesan</p>
            <button id="copy-token-btn" style="display:none;" class="button">Salin Token</button>
            <button onclick="closeAlert()">OK</button>
        </div>
    </div>
    <script src="<?= noCache('/js/main-kkn.js'); ?>"></script>

    <?php if ($flash = get_flash()): ?>
        <?php $type = array_key_first($flash); ?>
        <script>
            showAlert({
                type: <?= json_encode($type) ?>,
                title: <?= json_encode(ucfirst($type)) ?>,
                message: <?= json_encode($flash[$type]) ?>,
                token: <?= isset($flash['token']) ? json_encode($flash['token']) : 'null' ?>
            });
        </script>
    <?php endif; ?>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>