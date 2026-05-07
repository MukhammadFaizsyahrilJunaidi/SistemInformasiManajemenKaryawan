<?php
session_start();
require 'db.php';

// Ambil user_id dari session
$username = $_SESSION['username'];
if (empty($username)) {
    header('Location: auth.php');
    exit;
}
$isLogin = !empty($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Hybrid UI – Pastel Theme with Animations</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
    rel="stylesheet"
  />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    rel="stylesheet"
  />
  <style>
    :root {
  --primary-color: #5DADE2;
  --secondary-color: #b3cde0;
  --sidebar-width: 260px;
  --fab-size: 56px;
  --transition: 0.3s ease;
}

body {
  margin: 0;
  font-family: 'Segoe UI', sans-serif;
}

/* Navbar */
.navbar {
  background-color: var(--primary-color);
}

.navbar .navbar-brand,
.navbar .btn-outline-secondary,
.navbar .dropdown-toggle {
  color: #fff;
}

/* Tombol Offcanvas */
.navbar .btn-outline-secondary {
  border-color: rgba(255,255,255,0.5);
}

/* Search Input Focus Animation */
.search-wrapper {
  transition: width var(--transition);
  width: 30%;
}
.search-wrapper input {
  transition: box-shadow var(--transition), transform var(--transition);
  width: 100%;
}
.search-wrapper input:focus {
  box-shadow: 0 0 8px rgba(0,0,0,0.1);
  transform: translateY(-2px);
}

/* Offcanvas Sidebar */
.offcanvas-start {
  width: var(--sidebar-width);
  background-color: var(--secondary-color);
}

.offcanvas .nav-link {
  color: #333;
  transition: background var(--transition), padding-left var(--transition);
}

.offcanvas .nav-link:hover {
  background-color: rgba(0,0,0,0.05);
  padding-left: 1.5rem;
}

/* Kartu dengan Efek Fade-in-up */
.fade-in-up {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}
.fade-in-up.visible {
  opacity: 1;
  transform: translateY(0);
}

/* Styling Kartu */
.card {
  border: none;
  border-left: 4px solid var(--primary-color);
}
.card h6 {
  color: #6c757d;
}
.card h2 {
  font-weight: 700;
  color: var(--primary-color);
}

/* Floating Action Button */
.fab {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: var(--fab-size);
  height: var(--fab-size);
  border-radius: 50%;
  background-color: var(--primary-color);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: box-shadow var(--transition), transform 0.1s ease;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.fab:hover {
  box-shadow: 0 8px 16px rgba(0,0,0,0.25);
}

.fab:active {
  transform: scale(0.95);
}

/* Responsive */
@media (max-width: 767px) {
  form[role="search"] {
    display: none;
  }
}
  </style>
</head>
<body>

  <!-- Navbar Atas -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
      <button
        class="btn btn-outline-light me-2"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasMenu"
        aria-controls="offcanvasMenu"
      >
        <i class="bi bi-list"></i>
      </button>
      <a class="navbar-brand fw-bold" href="#">MyCompany</a>