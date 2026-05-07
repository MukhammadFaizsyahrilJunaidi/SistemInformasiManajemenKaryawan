<?php
declare(strict_types=1);
include 'navbar-1.php'; 
include 'navbar-2.php';

$divisi  = $pdo->query("SELECT id, nama FROM divisi ORDER BY nama")->fetchAll();
$jabatan = $pdo->query("SELECT id, nama FROM jabatan ORDER BY nama")->fetchAll();

if ($isLogin) {
  $stmt = $pdo->prepare("SELECT * FROM karyawan WHERE username = ?");
  $stmt->execute([$username]);
  $existing = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Cek sudah “filled” (tidak ada yang NULL / empty)
$filled = $existing
       && !empty($existing['nama'])
       && !empty($existing['nik'])
       && !empty($existing['divisi_id'])
       && !empty($existing['jabatan_id'])
       && !empty($existing['alamat'])
       && !empty($existing['email'])
       && !empty($existing['telepon'])
       && !empty($existing['foto']);

// Siapkan atribut disabled
$disabledInput = ($filled)  ? 'disabled' : '';
$requiredPhoto = (empty($existing['foto']) || !file_exists(__DIR__ . '/' . $existing['foto']))
    ? 'required'
    : '';
?>
<style>
  :root {
      --accent-color: var(--primary-color);
      --card-bg: rgba(255,255,255,0.8);
      --input-bg: rgba(255,255,255,0.6);
      --input-border: var(--secondary-color);
      --input-focus: var(--primary-color);
    }

    * { box-sizing: border-box; }

    body.karyawan-page {
      padding-top: 6rem;
      padding-bottom: 2rem;
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }

    .glass-card {
      background: var(--card-bg);
      backdrop-filter: blur(12px);
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
      padding: 2rem;
      animation: fadeIn 0.6s ease-out;
      transition: transform .3s;
    }
    .glass-card:hover {
      transform: translateY(-5px);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-control {
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: .5rem;
      padding: .75rem 1rem;
      transition: border-color .3s, box-shadow .3s;
      cursor: default;
    }
    .form-control:focus {
      border-color: var(--input-focus);
      box-shadow: 0 0 0 .2rem rgba(74,144,226,0.25);
      cursor: text;
    }

    .btn-accent {
      background-color: var(--accent-color);
      border: none;
      color: #fff;
      padding: .75rem 2rem;
      border-radius: .5rem;
      transition: background .3s, transform .2s;
    }
    .btn-accent:hover {
      filter: brightness(0.9) contrast(1.1);
      transform: translateY(-2px);
    }

    .img-preview {
      width: 130px;
      height: 130px;
      margin: 0 auto 1rem;
      border: 2px dashed var(--input-border);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--input-bg);
      transition: border-color .3s, box-shadow .3s;
      overflow: hidden;
    }
    .img-preview svg {
      width: 2.5rem;
      height: 2.5rem;
      fill: var(--input-border);
      transition: fill .3s, transform .3s;
    }
    .img-preview:hover svg {
      transform: scale(1.1);
    }
    .img-preview.loaded {
      border-color: var(--accent-color);
      box-shadow: 0 0 0 4px rgba(255,154,0,0.25);
    }
    .img-preview.loaded img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
</style>

<body class="karyawan-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="glass-card">
          <h3 class="text-center mb-4" style="color: var(--primary-color);">
            Form Data Karyawan
          </h3>
          <form id="employeeForm" action="simpan-hapus-karyawan.php" method="post" enctype="multipart/form-data">
            <div class="text-center mb-3">
              <div id="preview" class="img-preview" >
                <?php
                  if (!empty($existing['foto']) && file_exists(__DIR__ . '/' . $existing['foto'])) {
                  echo '<img src="' . htmlspecialchars($existing['foto']) . '" alt="Foto" style="width:100%; height:100%; object-fit:cover;">';
                  } else {
                ?>
                  <svg viewBox="0 0 24 24">
                  <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4
                          -4 1.79-4 4 1.79 4 4 4zm0 2c-2.67
                          0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                   </svg>
                <?php
                  }
                ?>
              </div>
              <input 
                type="file" 
                id="photoInput"
                name="foto"
                class="form-control form-control-sm"
                accept="image/*"
                <?= $disabledInput ?>
                <?= $requiredPhoto ?> 
              />
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                <input 
                  type="text" 
                  id="nama"
                  name="nama"
                  value="<?= htmlspecialchars($existing['nama'] ?? '') ?>"
                  class="form-control"
                  placeholder="Nama Lengkap *"
                  <?= $disabledInput ?>
                  required
                />
              </div>
              <div class="col-md-6">
                <input 
                  type="tel" 
                  id="nik"
                  name="nik"
                  class="form-control"
                  value="<?= htmlspecialchars($existing['nik'] ?? '') ?>"
                  placeholder="NIK *"
                  pattern="\d+"
                  inputmode="numeric"
                  maxlength="16"
                  <?= $disabledInput ?>
                  required
                />
              </div>
              <div class="col-md-6">
                <input 
                  type="email" 
                  id="email"
                  name="email"
                  value="<?= htmlspecialchars($existing['email'] ?? '') ?>"
                  class="form-control" 
                  placeholder="Email *" 
                  <?= $disabledInput ?>
                  required
                />
              </div>
              <div class="col-md-6">
                <input 
                  type="tel" 
                  id="phone"
                  name="telepon"
                  class="form-control"
                  value="<?= htmlspecialchars($existing['telepon'] ?? '') ?>"
                  placeholder="Telepon *"
                  pattern="\d+"
                  inputmode="numeric"
                  maxlength="15"
                  <?= $disabledInput ?>
                  required
                />
              </div>
              <div class="col-md-6">
                <select id="jabatan" class="form-control" name="jabatan_id" <?= $disabledInput ?> required>
                  <option value="" disabled <?= empty($existing['jabatan_id']) ? 'selected' : '' ?>>
                    Pilih jabatan *
                  </option>
                <?php foreach ($jabatan as $j): ?>
                   <option value="<?= $j['id'] ?>" <?= (($existing['jabatan_id'] ?? '') == $j['id']) ? 'selected' : '' ?>>
                                  <?= htmlspecialchars($j['nama']) ?>
                   </option>
                <?php endforeach ?>
                </select>
              </div>
              <div class="col-md-6">
                <select id="divisi" class="form-control" name="divisi_id" <?= $disabledInput ?> required>
                  <option value="" disabled <?= empty($existing['divisi_id']) ? 'selected' : '' ?>>
                    Pilih divisi *
                  </option>
                <?php foreach ($divisi as $d): ?>
                   <option value="<?= $d['id'] ?>" <?= (($existing['divisi_id'] ?? '') == $d['id']) ? 'selected' : '' ?>>
                                  <?= htmlspecialchars($d['nama']) ?>
                   </option>
                <?php endforeach ?>
                </select>
              </div>
              <div class="col-12">
                <textarea
                  id="address"
                  name="alamat"
                  class="form-control"
                  rows="3"
                  placeholder="Alamat Lengkap *"
                  <?= $disabledInput ?>
                  required
                ><?= htmlspecialchars($existing['alamat'] ?? '') ?></textarea>

              </div>
            </div>

            <div class="text-center mt-4">
              <?php if ($filled): ?>
              <div class="d-flex justify-content-center gap-2 mt-3">
  <!-- Tombol Edit -->
  <button type="button" name="action" value="edit" id="editBtn" class="btn-accent">
    Edit Data
  </button>

  <!-- Tombol Hapus -->
  <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
         class="bi bi-trash" viewBox="0 0 16 16">
      <path d="M5.5 5.5A.5.5 0 0 1 6 5h4a.5.5 0 0 1 
               .5.5v7a.5.5 0 0 1-1 0V6H6v6.5a.5.5 
               0 0 1-1 0v-7z"/>
      <path fill-rule="evenodd" 
            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 
               2 0 0 1-2 2H5a2 2 0 0 
               1-2-2V4h-.5a1 1 0 0 
               1 0-2h3.1a1 1 0 0 
               1 .9-.6h2a1 1 0 0 
               1 .9.6h3.1a1 1 0 0 1 1 1zM4.118 
               4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 
               0 0 0 1-1V4.059L11.882 4H4.118z"/>
    </svg>
  </button>
</div>

              <?php else: ?>
              <button type="submit" name="action" value="save" class="btn-accent">
                Simpan Data
              </button>
              <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit"
          form="employeeForm"
          name="action"
          value="delete"
          class="btn btn-danger"
          formnovalidate>Hapus</button>
      </div>
    </div>
  </div>
</div>
  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
  ></script>
  <script src="script.js"></script>
</body>
</html>