<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= noCache('/css/admin.css') ?>">
    <title>Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display&family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="admin-shell" data-page="<?= $currentPage ?? ' ' ?>">
    <?php partial('sidebar-admin'); ?>
    <div class='admin-main'>
        <?php partial('topbar-admin'); ?>

        <?= $content ?>

    </div>
    </main>
    <div id="custom-alert" class="alert-overlay">
        <div class="alert-box">
            <div class="alert-icon" id="alert-icon">✔</div>
            <h3 id="alert-title">Success</h3>
            <p id="alert-message">Pesan</p>
            <button id="copy-token-btn" style="display:none;" class="button">Salin Token</button>
            <button onclick="closeAlert()">OK</button>
        </div>
    </div>
    <script>
        const BASE_URL = '<?= url('') ?>';

        function kirimNilai() {
            const roleAdmin = document.getElementById("role-admin");
            const namaAdmin = document.getElementById("nama-admin");

            // json_encode() otomatis memberi tanda petik & menangani karakter khusus dengan aman
            namaAdmin.textContent = <?= json_encode($_SESSION['admin']['name'] ?? '') ?>;
            roleAdmin.textContent = <?= json_encode($_SESSION['admin']['role'] ?? '') ?>;

            // Ambil textContent dari elemen untuk dibandingkan
            if (roleAdmin.textContent !== "Kepala Admin") {
                document.getElementById("manageAdmin").style.display = "none";
            }
        }
        kirimNilai();
    </script>
    <script src="<?= noCache('/js/admin/admin.js') ?>"></script>

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