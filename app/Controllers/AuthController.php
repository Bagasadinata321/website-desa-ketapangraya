<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Admins;
use App\Services\MailService;

class AuthController extends BaseController
{
    public function showLogin(Request $request, Response $response)
    {
        return $response->view('auth.login', [], 'login');
    }
    public function showRegister(Request $request, Response $response)
    {
        return $response->view('auth.register', [], 'login');
    }
    // Memproses Pendaftaran Admin Baru
    public function registerProcess(Request $request, Response $response)
    {
        $userModel = new Admins();

        // Cek apakah aksi dilakukan oleh Kepala Admin (via Admin Panel) atau Registrasi Publik
        $isByAdmin = isset($_SESSION['admin']) && ($_SESSION['admin']['role'] ?? '') === 'Kepala Admin';

        // Tangkap ID jika ini adalah proses EDIT/UPDATE oleh Kepala Admin
        $rawId  = $request->input('id');
        $isUpdate = !empty($rawId);

        // Persiapkan data dasar dari input form
        $data = [
            'name'     => trim((string) $request->input('name', $request->input('nama', ''))),
            'email'    => trim((string) $request->input('email', '')),
            'username' => trim((string) $request->input('username', $request->input('nama', ''))),
        ];

        // Handing Password:
        // Jika CREATE -> Password wajib diisi.
        // Jika UPDATE -> Jika password diisi maka di-hash, jika kosong jangan ubah password lama.
        $password = (string) $request->input('password', '');
        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if ($isByAdmin) {
            // Jika ditambahkan/di-update oleh KEPALA ADMIN:
            // Gunakan role & status yang dipilih dari form, atau berikan nilai default yang aman
            $data['role']   = $request->input('role', 'Perangkat Desa');
            $data['status'] = $request->input('status', 'approved');
        } else {
            // Jika REGISTRASI MANDIRI PUBLIK:
            // Selalu set Role = 'Perangkat Desa' & Status = 'pending'
            $data['role']   = 'Perangkat Desa';
            $data['status'] = 'pending';
        }

        if ($isUpdate) {
            // --- PROSES UPDATE ---
            // Jika password kosong saat update, hapus kunci 'password_hash' dari array agar tidak menimpa hash lama dengan NULL
            if (empty($password)) {
                unset($data['password_hash']);
            }

            // Gunakan method update / updateFiltered bawaan model Anda
            $result = $userModel->update((int) $rawId, $data);

            $redirectUrl = $isByAdmin ? 'admin/administrator/list' : '/login';
            $message     = 'Data administrator berhasil diperbarui!';
        } else {
            // --- PROSES CREATE BARU ---
            $result = $userModel->createFiltered($data);

            // Hanya kirim notifikasi email jika pendaftaran dilakukan secara MANDIRI oleh user publik
            if ($result && !$isByAdmin) {
                MailService::sendAdminNotification([
                    'nama'  => $data['name'],
                    'email' => $data['email']
                ]);
            }

            $redirectUrl = $isByAdmin ? 'admin/administrator/list' : '/login';
            $message     = $isByAdmin
                ? 'Administrator baru berhasil ditambahkan!'
                : 'Pendaftaran berhasil! Akun Anda sedang menunggu verifikasi dari Kepala Admin Desa.';
        }

        // Mengembalikan respons menggunakan helper bawaan framework Anda
        return handle_result(
            $response,
            $result,
            $redirectUrl,
            $message
        );
    }

    // Memproses Login
    public function loginProcess(Request $request, Response $response)
    {
        $userModel = new Admins();
        $email     = $request->input('email');
        $password  = $request->input('password');

        // Menggunakan findOne() bawaan Base Model Anda
        $user = $userModel->findOne('email', $email);

        if ($user && password_verify($password, $user['password_hash'])) {

            // Cek Status Akses Verifikasi
            if ($user['status'] === 'pending') {
                return handle_result($response, false, '/login', 'Akun Anda belum diverifikasi oleh Super Admin.');
            }

            if ($user['status'] === 'rejected') {
                return handle_result($response, false, '/login', 'Pendaftaran akun Anda ditolak.');
            }

            // Jika status 'approved'
            $_SESSION['admin'] = $user;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role']    = $user['role'];

            return handle_result($response, true, '/admin/dashboard', 'Selamat datang!');
        }

        return handle_result($response, false, '/login', 'Email atau password salah.');
    }
    public function logout(Request $request, Response $response)
    {
        session_destroy();

        header("Location: " . url('/'));
        exit;
    }
}
