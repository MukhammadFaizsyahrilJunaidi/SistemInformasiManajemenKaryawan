<?php
include 'navbar-1.php'; 
include 'navbar-2.php';

// Ambil semua data karyawan
$sql = "SELECT k.nama, k.nik, k.email, k.alamat, k.telepon,
               k.foto, d.nama AS divisi, j.nama AS jabatan
        FROM karyawan k
        LEFT JOIN divisi d ON k.divisi_id = d.id
        LEFT JOIN jabatan j ON k.jabatan_id = j.id
        ORDER BY k.nama ASC";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Karyawan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: var(--bg-color,#f5f6fa); }
    .glass-card {
      background: rgba(255,255,255,0.2);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    table thead {
      background-color: var(--primary-color,#0d6efd);
      color: #fff;
    }
    table img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 50%;
    }
    td:last-child { /* alamat */
      white-space: normal;
      word-break: break-word;
    }

    /* Mobile friendly: ubah tabel jadi card list di layar kecil */
    @media (max-width: 767px) {
  table thead { display: none; }
  table, table tbody, table tr, table td { display: block; width: 100%; }
  table tr {
    margin-bottom: 1rem;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 8px;
    background: rgba(255,255,255,0.4);
    padding: 0.5rem;
  }
  table td {
    display: flex;
    align-items: flex-start;
    border: none;
  }
  table td::before {
    content: attr(data-label);
    font-weight: bold;
    margin-right: auto;
    white-space: normal;
    flex: 0 0 45%;
  }
  table td span {
    text-align: right;
    margin-left: auto;
    word-break: break-word;
  }
}
  </style>
</head>
<body class="karyawan-page">

<div class="container my-5">
  <div class="glass-card">
    <h3 class="text-center mb-4" style="color: var(--primary-color);">Daftar Semua Karyawan</h3>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Divisi</th>
            <th>Jabatan</th>
            <th>Alamat</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($rows): ?>
            <?php foreach ($rows as $row): ?>
              <tr>
                <td data-label="Foto">
                  <?php if (!empty($row['foto']) && file_exists(__DIR__.'/'.$row['foto'])): ?>
                    <img src="<?= htmlspecialchars($row['foto']) ?>" alt="Foto">
                  <?php else: ?>
                    <img src="uploads/default.png" alt="No Foto">
                  <?php endif; ?>
                </td>
                <td data-label="Nama"><?= htmlspecialchars($row['nama']) ?></td>
                <td data-label="NIK"><?= htmlspecialchars($row['nik']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                <td data-label="Telepon"><?= htmlspecialchars($row['telepon']) ?></td>
                <td data-label="Divisi"><?= htmlspecialchars($row['divisi'] ?? '-') ?></td>
                <td data-label="Jabatan"><?= htmlspecialchars($row['jabatan'] ?? '-') ?></td>
                <td data-label="Alamat"><span><?= nl2br(htmlspecialchars($row['alamat'])) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="8" class="text-center">Tidak ada data karyawan</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>