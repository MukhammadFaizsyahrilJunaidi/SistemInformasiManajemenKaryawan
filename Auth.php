<?php
declare(strict_types=1);

session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => !empty($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Generate CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$action    = $_POST['action']    ?? '';
$message   = '';
$form_mode = 'login';

// Helper functions
function getConnection(): mysqli {
    $conn = new mysqli('localhost', 'root', '', 'login_db');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn->set_charset('utf8mb4');
    return $conn;
}

function validateCsrf(): void {
    if (empty($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        exit('Invalid CSRF token');
    }
}

function redirectDashboard(string $username): void {
    session_regenerate_id(true);
    $_SESSION['username'] = $username;
    header('Location: index.php');
    exit;
}
try {
    // REGISTER
    if ($action === 'register') {
        validateCsrf();
        $form_mode = 'register';

        $u = trim($_POST['reg_username'] ?? '');
        $p = $_POST['reg_password'] ?? '';

        if ($u === '') {
            $message = 'Username tidak boleh kosong.';
        } elseif ($p === '') {
            $message = 'Password tidak boleh kosong.';
        } elseif (strlen($p) < 8) {
            $message = 'Password minimal 8 karakter.';
        }  else {
            $db  = getConnection();
            $chk = $db->prepare("
                SELECT 1 FROM users
                WHERE username = ?
            ");
            $chk->bind_param('s', $u);
            $chk->execute();

            if ($chk->get_result()->num_rows > 0) {
                $message = "Username $u sudah digunakan.";
            } else {
                $h   = password_hash($p, PASSWORD_DEFAULT);
                $ins = $db->prepare("
                    INSERT INTO users (username, password)
                    VALUES (?, ?)
                ");
                $ins->bind_param('ss', $u, $h);
                $ins->execute();

                $ins->close();
                $db->close();

                redirectDashboard( $u);
            }

            $db->close();
        }
    }

    // LOGIN
    if ($action === 'login') {
        validateCsrf();
        $form_mode = 'login';

        $u = trim($_POST['login_username'] ?? '');
        $p = $_POST['login_password'] ?? '';

        $db = getConnection();
        $stmt = $db->prepare("
            SELECT id, username, password
            FROM users
            WHERE username = ?
        ");
        $stmt->bind_param('s', $u);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $db->close();

        if ($u === '') {
            $message = 'Username tidak boleh kosong.';
        } elseif (! $row) {
            $message = 'Username tidak ditemukan.';
        } elseif ($p === '') {
            $message = 'Password tidak boleh kosong.';
        } elseif (! password_verify($p, $row['password'])) {
            $message = 'Password salah.';
        } else {
            redirectDashboard($row['username']);
        }
    }

} catch (Exception $e) {
    error_log($e->getMessage());
    $message = 'Terjadi kesalahan, silakan coba lagi.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login & Register — Elegant Contrast</title>

  <!-- Bootstrap CSS v5.3.7 -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <!-- Bootstrap Icons -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    rel="stylesheet"
  />

  <style>
    :root {
      --primary-color: #2C3E50;
      --middle-color: #447699;
      --secondary-color: #5DADE2;
      --card-radius: 1rem;
      --card-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
    }

    /* Global & Animated Gradient Background */
    * { box-sizing: border-box; }
    body, html {
      height: 100%; margin: 0; padding: 0;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      background-size: 400% 400%;
      animation: gradientBG 12s ease infinite;
      font-family: 'Poppins', sans-serif;
      overflow: hidden;
    }
    @keyframes gradientBG {
      0%   { background-position: 0% 50%; }
      50%  { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* Left Panel */
    .left-panel {
      position: relative;
      background: rgba(0,0,0,0.2);
      color: #fff;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 2rem;
    }
    .left-panel h1 {
      font-size: 3rem;
      animation: fadeInLeft 1s ease both;
    }
    .left-panel p {
      font-size: 1.25rem;
      margin-top: .5rem;
      animation: fadeInLeft 1.2s ease both;
    }
    @keyframes fadeInLeft {
      from { opacity: 0; transform: translateX(-2rem); }
      to   { opacity: 1; transform: translateX(0); }
    }
    /* Floating Circles */
    .left-panel .circle {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      animation: floatUp infinite ease-in-out;
    }
    @keyframes floatUp {
      0% { transform: translateY(0) scale(1); opacity: 0.3; }
      50%{ transform: translateY(-20px) scale(1.1); opacity: 0.5; }
      100%{ transform: translateY(0) scale(1); opacity: 0.3; }
    }

    /* Auth Card */
    .auth-card {
      background: #ffffff;
      backdrop-filter: blur(12px);
      border: none;
      border-radius: var(--card-radius);
      box-shadow: var(--card-shadow);
      overflow: hidden;
      max-width: 420px;
      width: 90%;
      animation: fadeInRight 1s ease both;
    }
    @keyframes fadeInRight {
      from { opacity: 0; transform: translateX(2rem); }
      to   { opacity: 1; transform: translateX(0); }
    }

    /* Decorative SVG Top */
    .decor-top {
      display: block;
      width: 100%;
      height: auto;
    }
    .decor-top path {
      fill: var(--middle-color);
      opacity: .8;
    }

    /* Form Controls & Cursor Fix */
    .form-control {
      cursor: default;
    }
    .form-control:focus {
      cursor: text;
    }
    .form-floating .form-control:focus {
      border-color: var(--middle-color);
      box-shadow: 0 0 0 .2rem rgba(68, 118, 153, 0.375);
    }
    .input-group-text {
      background: transparent;
      border: none;
      color: var(--middle-color);
    }
    .nav-pills .nav-link.active {
      background: var(--middle-color);
    }

    /* Password Toggle */
    .password-toggle {
      position: absolute;
      top: 50%; right: 1rem;
      transform: translateY(-50%);
      color: #6c757d;
      cursor: pointer;
      z-index: 2;
    }
    .password-toggle:hover {
      color: var(--middle-color);
    }
    /* letakkan ini di akhir file CSS Anda */
    .form-check-input,
    .form-check-label {
      cursor: pointer;
    }
    #termsCheck,               /* input */
    label[for="termsCheck"] {  /* label */
      cursor: pointer;
    }
    @media (max-width: 768px) {
  /* izinkan scroll dan auto height */
  body, html {
    overflow-y: auto;
  }
  .container-fluid,
  .row.h-100 {
    height: auto !important;
  }
  /* panel kiri full-width */
  .left-panel {
    display: flex !important;
    padding: 1rem;
    height: auto;
  }
  .left-panel h1 {
    font-size: 1.75rem; /* dari 3rem */
  }
  .left-panel p {
    font-size: 0.875rem; /* dari 1.25rem */
  }
  .left-panel .circle {
    display: none;
  }
  /* SCALE DOWN SELURUH CARD */
  .auth-card {
    max-width: 400px;
    top: 1rem;
    margin: 1rem auto;          /* posisikan di tengah */
    
    /* SHRINK SELURUH ISI */
    transform: scale(0.8);      /* ubah 0.8 sesuai kebutuhan */
    transform-origin: top center;
    
    /* dukung zoom untuk beberapa browser */
    zoom: 0.8;
  }
  /* Mengecilkan font dasar modal */
  #termsModal .modal-content {
    font-size: 0.875rem; /* setara ~14px */
    line-height: 1.4;
  }

  /* Judul sedikit lebih besar agar masih terlihat kontras */
  #termsModal .modal-header .modal-title {
    font-size: 1.25rem; /* setara ~20px */
  }

  /* Paragraf body agar konsisten */
  #termsModal .modal-body p {
    font-size: 0.875rem;
  }

  /* Label checkbox proporsional */
  #termsModal .form-check-label {
    font-size: 0.875rem;
  }
}
  </style>
</head>
<body>
<!-- Toast Container (posisi fixed di atas) -->
<div
  class="position-fixed top-0 start-50 translate-middle-x p-3"
  style="z-index: 1080;"
>
  <div
    id="errorToast"
    class="toast align-items-center text-bg-danger border-0"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-autohide="true"
    data-bs-delay="3000"
  >
    <div class="d-flex">
      <div class="toast-body">
        <?= htmlspecialchars($message) ?>
      </div>
      <button
        type="button"
        class="btn-close btn-close-white me-2 m-auto"
        data-bs-dismiss="toast"
        aria-label="Close"
      ></button>
    </div>
  </div>
</div>

  <div class="container-fluid h-100">
    <div class="row h-100">
      <!-- LEFT PANEL -->
      <div class="col-md-6 d-none d-md-flex left-panel">
        <h1>Selamat Datang</h1>
        <p>Masuk atau daftar untuk melanjutkan</p>
        <div class="circle" style="width:120px; height:120px; top:20%; left:15%; animation-duration:6s;"></div>
        <div class="circle" style="width:80px;  height:80px;  top:60%; left:70%; animation-duration:4s;"></div>
        <div class="circle" style="width:100px; height:100px; top:80%; left:30%; animation-duration:5s;"></div>
      </div>

      <!-- RIGHT PANEL: AUTH CARD -->
      <div class="col-md-6 d-flex align-items-center justify-content-center">
        <div class="card auth-card">

          <!-- SVG DECORATION -->
          <svg class="decor-top" viewBox="0 0 500 150" preserveAspectRatio="none">
            <path d="M0,0 C150,100 350,0 500,100 L500,00 L0,0 Z"></path>
          </svg>

          <div class="card-body p-4">
            <!-- TABS -->
            <ul class="nav nav-pills justify-content-center mb-4" role="tablist">
  <li class="nav-item" role="presentation">
    <button
      class="nav-link <?= $form_mode === 'login' ? 'active' : '' ?>"
      id="tab-login"
      data-bs-toggle="pill"
      data-bs-target="#pane-login"
      type="button"
      aria-controls="pane-login"
      aria-selected="<?= $form_mode === 'login' ?>"
    >
      Login
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button
      class="nav-link <?= $form_mode === 'register' ? 'active' : '' ?>"
      id="tab-register"
      data-bs-toggle="pill"
      data-bs-target="#pane-register"
      type="button"
      aria-controls="pane-register"
      aria-selected="<?= $form_mode === 'register' ?>"
    >
      Register
    </button>
  </li>
</ul>

            <div class="tab-content">
              <!-- LOGIN FORM -->
              <div
                class="tab-pane fade <?= $form_mode==='login' ? 'show active' : '' ?>"
                id="pane-login"
              >
                <form method="post" action="">
                  <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= $_SESSION['csrf_token'] ?>"
                  />
                  <input type="hidden" name="action" value="login" />

                  <div class="form-floating mb-3 position-relative">
                    <input
                      type="text"
                      class="form-control"
                      id="loginUsername"
                      name="login_username"
                      placeholder="Username"
                      value="<?= htmlspecialchars($_POST['login_username'] ?? '') ?>"
                    />
                    <label for="loginUsername">Username</label>
                  </div>

                  <div class="form-floating mb-3 position-relative">
                    <input
                      type="password"
                      class="form-control"
                      id="loginPassword"
                      name="login_password"
                      placeholder="Password"
                      value="<?= htmlspecialchars($_POST['login_password'] ?? '') ?>"

                    />
                    <label for="loginPassword">Password</label>
                    <span class="password-toggle" data-target="loginPassword">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>

                  <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg">
                      Masuk
                    </button>
                  </div>

                  <div class="text-center">
                    <a href="#" class="small text-decoration-none">
                      Lupa password?
                    </a>
                  </div>
                </form>
              </div>

              <!-- REGISTER FORM -->
              <div
                class="tab-pane fade <?= $form_mode==='register' ? 'show active' : '' ?>"
                id="pane-register"
              >
                <form method="post" action="">
                  <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= $_SESSION['csrf_token'] ?>"
                  />
                  <input type="hidden" name="action" value="register" />

                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="regUsername"
                      name="reg_username"
                      placeholder="Username"
                      value="<?= htmlspecialchars($_POST['reg_username'] ?? '') ?>"
                    />
                    <label for="regUsername">Username</label>
                  </div>

                  <div class="form-floating mb-3 position-relative">
                    <input
                      type="password"
                      class="form-control"
                      id="regPassword"
                      name="reg_password"
                      placeholder="Password"
                      value="<?= htmlspecialchars($_POST['reg_password'] ?? '') ?>"
                    />
                    <label for="regPassword">Password</label>
                    <span class="password-toggle" data-target="regPassword">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>

                  <div class="form-check mb-3">
  <input
    class="form-check-input"
    type="checkbox"
    id="termsCheck"
    name="terms_check"
    required
  />
  <label class="form-check-label" for="termsCheck">
    Saya setuju dengan syarat & ketentuan
  </label>

  <div class="invalid-feedback">
    Anda harus menyetujui syarat & ketentuan sebelum melanjutkan.
  </div>
</div>


                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">
                      Daftar
                    </button>
                  </div>
                </form>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>


 <div class="modal fade" id="termsModal" tabindex="-1"
     aria-labelledby="termsModalLabel" aria-hidden="true">
  <div class="modal-dialog
              modal-lg
              modal-dialog-scrollable
              modal-fullscreen-md-down">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="termsModalLabel">
          Syarat & Ketentuan
        </h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Tutup">
        </button>
      </div>
      <div class="modal-body">
        <!-- Isi panjang syarat & ketentuan Anda -->
        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quia hic adipisci quam deleniti repudiandae neque, nesciunt totam, explicabo quasi, velit sunt voluptatibus dicta exercitationem dolor nemo. Eius reprehenderit ex mollitia.</p>

        <div class="form-check mt-4">
          <input
            class="form-check-input"
            type="checkbox"
            id="modalAgreeCheck"
          />
          <label class="form-check-label" for="modalAgreeCheck">
            Saya telah membaca dan menyetujui syarat & ketentuan di atas
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
  <!-- Bootstrap JS Bundle -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
  ></script>
  <script>
    // Password Show/Hide Toggle
    document.querySelectorAll('.password-toggle').forEach(el => {
      el.addEventListener('click', () => {
        const input = document.getElementById(el.dataset.target);
        const icon  = el.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
          input.type = 'password';
          icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
      });
    });
    document.addEventListener('DOMContentLoaded', () => {
    <?php if (!empty($message)): ?>
      const toastEl = document.getElementById('errorToast');
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    <?php endif; ?>

  const form          = document.getElementById('registerForm');
  const initialCheck  = document.getElementById('termsCheck');
  const modalCheck    = document.getElementById('modalAgreeCheck');
  const bsModal       = new bootstrap.Modal(document.getElementById('termsModal'));

  // 1) Klik checkbox awal → buka modal, batalkan toggle default
  initialCheck.addEventListener('click', event => {
    event.preventDefault();
    bsModal.show();
  });

  // 2) Saat modal checkbox berubah
  modalCheck.addEventListener('change', () => {
    // aktif/non-aktifkan tombol Setuju & Tutup

    // jika user melepas centang di modal → lepaskan juga di form
    if (!modalCheck.checked) {
      initialCheck.checked = false;
    } else {
      initialCheck.checked = true;
    }
  });

  // 3) Validasi sebelum submit
  form.addEventListener('submit', event => {
    if (!initialCheck.checked) {
      event.preventDefault();
      initialCheck.classList.add('is-invalid');
    }
  });
});
  </script>
</body>
</html>