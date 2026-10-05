<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public static function sendAdminNotification(array $pendaftar): bool
    {
        // 1. Ambil seluruh konfigurasi SMTP utama dari file config/mail.php
        $config = require __DIR__ . '/../../config/mail.php';

        // 2. Ambil Email Penerima dari database settings (dinamis), jika kosong fallback ke file config/mail.php
        $targetEmail = setting('superadmin_email', $config['superadmin_email'] ?? $config['smtp_user']);

        $mail = new PHPMailer(true);

        try {
            // Konfigurasi Server SMTP Gmail (Terikunci Aman di Config)
            $mail->isSMTP();
            $mail->Host       = $config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $config['smtp_user'];
            $mail->Password   = $config['smtp_pass'];

            // Enkripsi SSL/TLS sesuai port di config
            if ((int)$config['smtp_port'] === 465) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }
            $mail->Port       = $config['smtp_port'];

            // Pengirim (dari config) & Penerima Notifikasi (dari Settings Admin Panel)
            $mail->setFrom($config['smtp_user'], $config['from_name']);
            $mail->addAddress($targetEmail, 'Super Admin Desa');

            // Konten Email Notifikasi
            $mail->isHTML(true);
            $mail->Subject = "Notifikasi Pendaftaran Admin Baru: " . htmlspecialchars($pendaftar['nama'] ?? '-');
            $mail->Body    = "
                <h3>Ada pendaftaran admin baru di Website Desa</h3>
                <p><b>Nama Pendaftar:</b> " . htmlspecialchars($pendaftar['nama'] ?? '-') . "</p>
                <p><b>Email Pendaftar:</b> " . htmlspecialchars($pendaftar['email'] ?? '-') . "</p>
                <p>Status: <b style='color: orange;'>PENDING</b></p>
                <br>
                <p>Silakan login ke Dashboard Super Admin untuk menyetujui atau menolak akun ini.</p>
            ";

            return $mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
