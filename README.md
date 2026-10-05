# 🏞️ Ketapang Raya – Website Profil Desa & Custom CMS Engine

🌐 **Live Website:** [https://ketapangraya.desa.id](https://ketapangraya.site)
💻 **Source Code:** [https://github.com/Bagasadinata321/website-desa-ketapangraya](https://github.com/Bagasadinata321/website-desa-ketapangraya)

Platform sistem informasi publik dan Content Management System (CMS) terintegrasi untuk Desa Ketapang Raya. Bertenagakan **PHP Native MVC** yang dikembangkan dari nol tanpa framework pihak ketiga, aplikasi ini memudahkan pengelolaan data potensi desa, destinasi ekowisata, produk UMKM, hingga struktur organisasi desa secara dinamis.

---

## 🚀 Fitur Utama

### 🌐 Public Front-End
- **Dynamic Portal:** Penayangan profil desa, galeri destinasi wisata, katalog produk UMKM, serta program kerja KKN.
- **RESTful Clean Routing:** Akses URL ramah SEO (contoh: `/destinasi/{slug}`).
- **SEO Sitemap Generator:** Peta situs otomatis via `/sitemap.xml` untuk pengindeksan mesin pencari.

### ⚙️️ Custom CMS Engine (Admin Panel)
- **Data-Driven Resource Mapper:** Arsitektur Single Controller (`AdminController`) berbasis konfigurasi `$resourceMap` untuk pengelolaan CRUD dinamis secara DRY (*Don't Repeat Yourself*).
- **Section & Repeater Field Builder:** Editor visual untuk mengelola section dinamis (Hero, Statistik/Data KK, Quotes) yang dikonversi ke JSON Meta.
- **Polymorphic Media Management:** Sistem media terpusat (`media_relations`) untuk foto sampul dan galeri entitas secara efisien.
- **Role & Approval Management:** Otorisasi admin berbasis sesi dengan sistem persetujuan pendaftaran admin baru.

---

## 🛠️ Tech Stack
- **Core Engine:** PHP Native 8.x (Custom MVC Architecture)
- **Database:** MySQL / MariaDB (PDO Prepared Statements)
- **Frontend:** Blade-like View Renderer, Bootstrap / Tailwind CSS, JavaScript (AJAX)
- **Security:** BCrypt Password Hashing, Multi-session Middleware Segregation

---

## 💻 Cara Menjalankan di Lokal

```bash
# Clone repository
git clone [https://github.com/Bagasadinata321/website-desa-ketapangraya.git](https://github.com/Bagasadinata321/website-desa-ketapangraya.git)

# Masuk ke direktori proyek
cd website-desa-ketapangraya

# Import database
# Salin file .sql yang tersedia ke MySQL / MariaDB lokal kamu

# Konfigurasi database
# Buat file config/database.php dan app/core/database.php sesuaikan dengan kredensial MySQL lokal
