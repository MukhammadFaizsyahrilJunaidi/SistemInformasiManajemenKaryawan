<div class="d-flex align-items-center">
        <button class="btn btn-link position-relative me-3">
          <i class="bi bi-bell fs-4 text-light"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            3
          </span>
        </button>
<div class="dropdown">
          <a
            href="#"
            class="d-flex align-items-center text-decoration-none dropdown-toggle"
            data-bs-toggle="dropdown"
          >
            <!-- SVG Siluet Avatar -->
            <svg width="40" height="40" viewBox="0 0 24 24" fill="#ffffffff" xmlns="http://www.w3.org/2000/svg">
  				<path d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4 8 5.79 8 8s1.79 4 4 4z"/>
  				<path d="M4 20v-1c0-3.87 3.13-7 7-7h2c3.87 0 7 3.13 7 7v1H4z"/>
			</svg>

            <span class="d-none d-md-inline"><?= htmlspecialchars($_SESSION['username']) ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Profil</a></li>
            <li><a class="dropdown-item" href="#">Pengaturan</a></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>

  <!-- Offcanvas Menu -->
  <div
    class="offcanvas offcanvas-start"
    tabindex="-1"
    id="offcanvasMenu"
    aria-labelledby="offcanvasMenuLabel"
  >
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu Utama</h5>
      <button
        type="button"
        class="btn-close text-reset"
        data-bs-dismiss="offcanvas"
        aria-label="Close"
      ></button>
    </div>
    <div class="offcanvas-body p-0">
      <ul class="nav nav-pills flex-column">
        <li class="nav-item">
          <a href="index.php" class="nav-link">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a href="karyawan.php" class="nav-link">
            <i class="bi bi-person-plus me-2"></i> Tambah Karyawan
          </a>
        </li>
        <li class="nav-item">
          <a href="data-karyawan.php" class="nav-link">
            <i class="bi bi-people me-2"></i> Daftar Karyawan
          </a>
        </li>
      </ul>
    </div>
</div>