<?php
session_start();
require 'db.php';

if (empty($_SESSION['username'])) {
  header('Location: auth.php');
  exit;
}
$username = $_SESSION['username'];

// Hapus bila action=delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
  $pdo->prepare("DELETE FROM karyawan WHERE username = ?")
      ->execute([$username]);
  header('Location: karyawan.php?status=deleted');
  exit;
}

// Sanitasi input
$nama      = trim($_POST['nama'] ?? '');
$nik       = trim($_POST['nik'] ?? '');
$divisiId  = filter_input(INPUT_POST, 'divisi_id', FILTER_VALIDATE_INT);
$jabatanId = filter_input(INPUT_POST, 'jabatan_id', FILTER_VALIDATE_INT);
$email     = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$alamat    = trim($_POST['alamat'] ?? '');
$telepon   = trim($_POST['telepon'] ?? '');
$fotoPath  = null;

// Debug: pastikan POST dan FILE ada
// var_dump($_POST, $_FILES); exit;

// Upload foto (jika ada)
if (!empty($_FILES['foto']['tmp_name'])) {
  $ext      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
  $filename = $username . '_' . time() . '.' . $ext;
  $folder   = __DIR__ . '/uploads/';
  if (!is_dir($folder)) mkdir($folder, 0755);
  if (move_uploaded_file($_FILES['foto']['tmp_name'], $folder . $filename)) {
    $fotoPath = 'uploads/' . $filename;
  } else {
    die('Gagal upload foto.');
  }
}

// Jika tidak ada foto baru, pertahankan yang lama atau default
if ($fotoPath === null) {
  // ambil existing (jika ada)
  $row = $pdo->prepare("SELECT foto FROM karyawan WHERE username = ?");
  $row->execute([$username]);
  $old = $row->fetchColumn();
  $fotoPath = $old ?: 'uploads/default.png';
}

// Pastikan ada unique index di username
// ALTER TABLE karyawan ADD UNIQUE (username);

$sql = "
  INSERT INTO karyawan
    (username, divisi_id, jabatan_id, nama, nik, email, alamat, telepon, foto)
  VALUES
    (:username, :div, :jab, :nama, :nik, :email, :alamat, :telp, :foto)
  ON DUPLICATE KEY UPDATE
    divisi_id  = VALUES(divisi_id),
    jabatan_id = VALUES(jabatan_id),
    nama       = VALUES(nama),
    nik        = VALUES(nik),
    email      = VALUES(email),
    alamat     = VALUES(alamat),
    telepon    = VALUES(telepon),
    foto       = VALUES(foto)
";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':username' => $username,
    ':div'      => $divisiId,
    ':jab'      => $jabatanId,
    ':nama'     => $nama,
    ':nik'      => $nik,
    ':email'    => $email,
    ':alamat'   => $alamat,
    ':telp'     => $telepon,
    ':foto'     => $fotoPath
  ]);

  header('Location: karyawan.php?status=success');
  exit;