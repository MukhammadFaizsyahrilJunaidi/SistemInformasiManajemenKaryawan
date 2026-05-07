/* navbar.php */
    // Inisialisasi tooltip Bootstrap
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    // IntersectionObserver untuk efek fade-in pada kartu
    const observer = new IntersectionObserver((entries, obs) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.2 });

    document.querySelectorAll('.fade-in-up').forEach(card => observer.observe(card));

/* karyawan.php */
    const photoInput = document.getElementById('photoInput');
    const preview = document.getElementById('preview');

    photoInput.addEventListener('change', () => {
      const file = photoInput.files[0];
      if (!file) return;
      const img = document.createElement('img');
      img.onload = () => preview.classList.add('loaded');
      img.src = URL.createObjectURL(file);
      preview.innerHTML = '';
      preview.appendChild(img);
    });
    document.addEventListener('DOMContentLoaded', () => {
  const editBtn = document.getElementById('editBtn');
  if (!editBtn) return;

  editBtn.addEventListener('click', () => {
    // Aktifkan semua input, select, textarea di form ini
    document.querySelectorAll('#employeeForm input, #employeeForm select, #employeeForm textarea')
      .forEach(el => el.disabled = false);

    // Opsional: sembunyikan tombol edit, munculkan tombol submit
    const saveBtn = document.createElement('button');
    saveBtn.type = 'submit';
    saveBtn.name = 'action';
    saveBtn.value = 'save';
    saveBtn.className = 'btn-accent';
    saveBtn.textContent = 'Simpan Data';

    editBtn.replaceWith(saveBtn);
  });
});