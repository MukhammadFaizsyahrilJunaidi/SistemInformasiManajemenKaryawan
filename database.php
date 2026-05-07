<?php
// Config koneksi
$dbHost  = 'localhost';
$dbName  = 'login_db';
$dbUser  = 'Faiz';
$dbPass  = 'Faiz1234';
$dsn     = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Inisiasi PDO
try {
  $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
} catch (PDOException $e) {
  die('Koneksi gagal: ' . $e->getMessage());
}

/**
 * Ambil semua karyawan (join divisi + jabatan)
 */
function getKaryawans(): array {
  global $pdo;
  $sql = "SELECT k.*, d.nama AS divisi, j.nama AS jabatan
          FROM karyawan k
          JOIN divisi d ON k.divisi_id = d.id
          JOIN jabatan j ON k.jabatan_id = j.id
          ORDER BY k.id";
  return $pdo->query($sql)->fetchAll();
}

/**
 * Ambil daftar divisi
 */
function getDivisi(): array {
  global $pdo;
  return $pdo->query("SELECT id, nama FROM divisi ORDER BY nama")->fetchAll();
}

/**
 * Ambil daftar jabatan
 */
function getJabatan(): array {
  global $pdo;
  return $pdo->query("SELECT id, nama FROM jabatan ORDER BY nama")->fetchAll();
}

/**
 * Tambah karyawan baru
 */
function addKaryawan(array $data, array $file): bool {
  global $pdo;
  // 1. Upload foto
  $uploadDir = __DIR__ . '/uploads/';
  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }
  $ext     = pathinfo($file['name'], PATHINFO_EXTENSION);
  $fname   = uniqid('foto_', true) . '.' . $ext;
  $target  = $uploadDir . $fname;
  if (!move_uploaded_file($file['tmp_name'], $target)) {
    return false;
  }
  $fotoUrl = 'uploads/' . $fname;

  // 2. Insert ke DB
  $sql = "INSERT INTO karyawan
          (nama, jenis_kelamin, divisi_id, jabatan_id, alamat, email, telepon, foto)
          VALUES
          (:nama, :jk, :div, :jab, :alamat, :email, :telp, :foto)";
  $stmt = $pdo->prepare($sql);
  return $stmt->execute([
    ':nama'      => $data['nama'],
    ':jk'        => $data['jenis_kelamin'],
    ':div'       => $data['divisi_id'],
    ':jab'       => $data['jabatan_id'],
    ':alamat'    => $data['alamat'],
    ':email'     => $data['email'],
    ':telp'      => $data['telepon'],
    ':foto'      => $fotoUrl,
  ]);
}