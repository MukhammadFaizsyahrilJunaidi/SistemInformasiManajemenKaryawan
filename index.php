<?php include 'navbar-1.php'; ?>
<form class="d-none d-md-flex mx-auto search-wrapper" role="search">
        <input
          class="form-control"
          type="search"
          placeholder="Cari karyawan..."
          aria-label="Search"
        />
      </form>
<?php include 'navbar-2.php'; ?>
<!-- Konten Utama -->
  <main class="container-fluid pt-5 mt-4">
    <div class="row g-4">
      <!-- Tambahkan kelas fade-in-up pada setiap kartu -->
      <div class="col-md-3">
        <div class="card shadow-sm h-100 fade-in-up">
          <div class="card-body">
            <h6>Total Karyawan</h6>
            <h2>1,245</h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm h-100 fade-in-up">
          <div class="card-body">
            <h6>Karyawan Baru</h6>
            <h2>27</h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm h-100 fade-in-up">
          <div class="card-body">
            <h6>Kehadiran Hari Ini</h6>
            <h2>1,102</h2>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm h-100 fade-in-up">
          <div class="card-body">
            <h6>Izin Tertunda</h6>
            <h2>5</h2>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Floating Action Button dengan tooltip -->
  <button
    class="btn fab"
    data-bs-toggle="tooltip"
    data-bs-placement="left"
    title="Tambah Karyawan"
    aria-label="Tambah"
  >
    <i class="bi bi-plus-lg fs-4"></i>
  </button>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
</body>
</html>