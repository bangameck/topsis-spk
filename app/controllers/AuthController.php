<?php
require_once __DIR__ . '/../../config/controlWeb.php';

class AuthController
{
    private $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * Menangani proses registrasi user baru (Masyarakat)
     */
    public function processRegister()
    {
        // 1. Validasi dasar
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            toastNotif('error', 'Metode tidak diizinkan.');
            header('Location: ' . base_url('auth/register'));
            exit();
        }

        if (!check_csrf_token($_POST['csrf_token'] ?? '')) {
            toastNotif('error', 'Token CSRF tidak valid.');
            header('Location: ' . base_url('auth/register'));
            exit();
        }

        // 2. Ambil dan bersihkan data dari form
        $username = $this->db->real_escape_string($_POST['username']);
        $password = $_POST['password'];
        $nik = $this->db->real_escape_string($_POST['nik']);
        $nama = $this->db->real_escape_string($_POST['nama']);
        $alamat = $this->db->real_escape_string($_POST['alamat']);
        $tempat_lahir = $this->db->real_escape_string($_POST['tempat_lahir']);
        $tanggal_lahir = $this->db->real_escape_string($_POST['tanggal_lahir']);
        $jenis_kelamin = $this->db->real_escape_string($_POST['jenis_kelamin']);

        // Validasi NIK harus 16 digit angka
        if (!preg_match('/^[0-9]{16}$/', $nik)) {
            toastNotif('error', 'NIK harus terdiri dari 16 digit angka.');
            header('Location: ' . base_url('auth/register'));
            exit();
        }

        // 3. Cek duplikasi username dan NIK
        $stmt = $this->db->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            toastNotif('error', 'Username sudah terdaftar.');
            header('Location: ' . base_url('auth/register'));
            exit();
        }
        $stmt->close();

        $stmt = $this->db->prepare("SELECT id FROM masyarakat WHERE nik = ?");
        $stmt->bind_param("s", $nik);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            toastNotif('error', 'NIK sudah terdaftar.');
            header('Location: ' . base_url('auth/register'));
            exit();
        }
        $stmt->close();

        // 4. Handle Upload File
        $profileImgPath = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR;
        $ktpImgPath = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'ktp' . DIRECTORY_SEPARATOR;

        $profileImgName = $this->_handleFileUpload('profile_pic', $profileImgPath);
        $ktpImgName = $this->_handleFileUpload('ktp_pic', $ktpImgPath);

        // 5. Gunakan Transaksi Database untuk integritas data
        $this->db->begin_transaction();

        try {
            // Insert ke tabel 'users'
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $level = 2; // Level 2 untuk Masyarakat

            $stmtUser = $this->db->prepare("INSERT INTO users (username, password, name, level, img) VALUES (?, ?, ?, ?, ?)");
            $stmtUser->bind_param("sssis", $username, $passwordHash, $nama, $level, $profileImgName);
            $stmtUser->execute();

            // Ambil user_id yang baru saja dibuat
            $new_user_id = $this->db->insert_id;
            if ($new_user_id == 0) {
                throw new Exception("Gagal mendapatkan ID user baru.");
            }

            // Insert ke tabel 'masyarakat'
            $stmtMasyarakat = $this->db->prepare("INSERT INTO masyarakat (user_id, nik, nama, alamat, tempat_lahir, tanggal_lahir, jenis_kelamin, ktp_img) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmtMasyarakat->bind_param("isssssss", $new_user_id, $nik, $nama, $alamat, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $ktpImgName);
            $stmtMasyarakat->execute();

            // Jika semua berhasil, commit transaksi
            $this->db->commit();

            toastNotif('success', 'Registrasi berhasil! Silakan login.');
            header('Location: ' . base_url('login.php')); // Arahkan ke halaman login
            exit();

        } catch (Exception $e) {
            // Jika terjadi error, rollback semua perubahan
            $this->db->rollback();

            // Hapus file yang mungkin sudah ter-upload
            if ($profileImgName && file_exists($profileImgPath . $profileImgName)) {
                unlink($profileImgPath . $profileImgName);
            }
            if ($ktpImgName && file_exists($ktpImgPath . $ktpImgName)) {
                unlink($ktpImgPath . $ktpImgName);
            }

            error_log('Registrasi Gagal: ' . $e->getMessage());
            toastNotif('error', 'Registrasi gagal. Terjadi kesalahan pada server.');
            header('Location: ' . base_url('auth/register'));
            exit();
        }
    }

    /**
     * Helper function untuk menangani upload file
     * @param string $fileInputName Nama dari <input type="file">
     * @param string $uploadPath Path direktori tujuan
     * @return string|null Nama file yang di-upload atau null jika gagal/tidak ada file
     */
    private function _handleFileUpload($fileInputName, $uploadPath)
    {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES[$fileInputName]['tmp_name'];
            $name_tmp = $_FILES[$fileInputName]['name'];
            $ext_valid = ['png', 'jpg', 'jpeg', 'gif'];
            $x = explode('.', $name_tmp);
            $extend = strtolower(end($x));
            $foto = uniqid() . '.' . $extend;

            if (in_array($extend, $ext_valid)) {
                // Gunakan fungsi kompresi yang sudah ada
                if (fotoCompressResize($foto, $file_tmp, $uploadPath) !== false) {
                    return $foto; // Return nama file jika berhasil
                } else {
                    toastNotif('error', 'Gagal memproses gambar ' . $name_tmp);
                    return null;
                }
            } else {
                toastNotif('error', 'Jenis file tidak didukung untuk ' . $name_tmp);
                return null;
            }
        }
        return null;
    }
}

?>