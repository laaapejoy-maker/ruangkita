<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RuangKita – Booking Ruang Kampus</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body class="landing-page">

  <!-- NAVBAR -->
  <nav id="navbar">
    <div class="nav-inner">
      <a href="#" class="logo">
        <div class="logo-icon">
          <img src="img/logo.png" alt="Logo RuangKita">
        </div>
        RuangKita
      </a>

      <style>
        .logo-icon img {
          width: 24px;
          height: 24px;
          object-fit: contain;
          display: block;
        }
      </style>
      <ul class="nav-links">
        <li><a href="#home">Home</a></li>
        <li><a href="#fitur">Fitur</a></li>
        <li><a href="#cara-kerja">Tentang</a></li>
        <li><a href="#kontak">Kontak</a></li>
      </ul>
      <?php if (isset($_SESSION['login'])): ?>
        <a href="logout.php" class="btn-login" style="background:var(--gray-600);">Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn-login">Login</a>
      <?php endif; ?>
      <div class="hamburger" id="hamburger" onclick="toggleMenu()">
        <span></span><span></span><span></span>
      </div>
    </div>
  </nav>
  <div class="mobile-menu" id="mobileMenu">
    <a href="#home" onclick="toggleMenu()">Home</a>
    <a href="#fitur" onclick="toggleMenu()">Fitur</a>
    <a href="#cara-kerja" onclick="toggleMenu()">Tentang</a>
    <a href="#kontak" onclick="toggleMenu()">Kontak</a>
    <?php if (isset($_SESSION['login'])): ?>
      <a href="logout.php" class="btn-login" style="background:var(--gray-600);">Logout</a>
    <?php else: ?>
      <a href="login.php" class="btn-login">Login</a>
    <?php endif; ?>
  </div>

  <!-- HERO -->
  <section id="home" style="padding: 0;">
    <div class="hero">
      <div class="hero-content">
        <div class="hero-badge"><span class="dot"></span> Sistem Informasi Kampus</div>
        <h1>Solusi Booking Ruang Kampus yang <span>Mudah</span> dan <em>Cepat</em></h1>
        <p>RuangKita memudahkan seluruh civitas akademika dalam mengajukan, mengelola, dan memantau peminjaman ruang kampus secara online — kapan saja, di mana saja.</p>
        <div class="hero-cta">
          <a href="memilih_ruangan.php" class="btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
            </svg>
            Mulai Booking
          </a>
          <a href="#fitur" class="btn-outline">
            Pelajari Lebih Lanjut
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
              <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
          </a>
        </div>
        <div class="hero-stats">
          <div class="stat-item">
            <div class="stat-num">50<span>+</span></div>
            <div class="stat-label">Ruang Tersedia</div>
          </div>
          <div class="stat-item">
            <div class="stat-num">2K<span>+</span></div>
            <div class="stat-label">Pengguna Aktif</div>
          </div>
          <div class="stat-item">
            <div class="stat-num">98<span>%</span></div>
            <div class="stat-label">Tingkat Kepuasan</div>
          </div>
        </div>
      </div>

      <div class="hero-visual">
      <div class="hero-card-main">
        <p class="card-title">Ketersediaan Ruang — Hari Ini</p>
        <div class="room-grid">
          <div class="room-slot available">
            <div class="slot-name">Aula Utama</div>
            <div class="slot-time">09.00 – 12.00</div>
            ✓ Tersedia
          </div>
          <div class="room-slot booked">
            <div class="slot-name">Lab Komputer 1</div>
            <div class="slot-time">08.00 – 14.00</div>
            ✗ Terpakai
          </div>
          <div class="room-slot pending">
            <div class="slot-name">Ruang Seminar B</div>
            <div class="slot-time">13.00 – 16.00</div>
            ⏳ Pending
          </div>
          <div class="room-slot available">
            <div class="slot-name">Ruang Rapat 3</div>
            <div class="slot-time">10.00 – 13.00</div>
            ✓ Tersedia
          </div>
        </div>
        <div class="booking-confirm">
          <div class="confirm-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </div>
          <div class="confirm-text">
            <div class="t1">Booking Disetujui!</div>
            <div class="t2">Aula Utama · Senin, 09.00 – 12.00</div>
          </div>
        </div>
      </div>
      <div class="floating-badge fb-1">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="2.5"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        <span style="color: var(--gray-800);">Notifikasi aktif</span>
      </div>
      <div class="floating-badge fb-2">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="2.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        <span style="color: var(--gray-800);">3 mahasiswa online</span>
      </div>
    </div>
    </div>
  </section>

 

  <!-- FOOTER -->
  <footer>
    <div class="footer-inner">
      <div class="footer-brand">
        <a href="#" class="logo" style="color:white; font-size:1.2rem; display:flex; align-items:center; gap:8px; text-decoration:none;">
          <div class="logo-icon">
            <img src="img/logo.png" alt="Logo">
          </div>
          RuangKita
        </a>
        <p>Sistem informasi peminjaman ruang kampus yang modern, efisien, dan transparan untuk seluruh civitas akademika.</p>
      </div>
      <div class="footer-col">
        <h4>Navigasi</h4>
        <ul>
          <li><a href="#home">Home</a></li>
          <li><a href="#fitur">Fitur</a></li>
          <li><a href="#cara-kerja">Cara Kerja</a></li>
          <li><a href="#kontak">Kontak</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Kontak</h4>
        <ul>
          <li><a href="mailto:support@ruangkita.id">support@ruangkita.id</a></li>
          <li><a href="tel:+62211234567">(021) 123-4567</a></li>
          <li><a href="#">Kampus Utama, UPI PURWAKARTA</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2025 <a href="#">RuangKita UPI PWK</a>. Hak cipta dilindungi.</span>
      <span>Dibuat dengan ❤️ untuk kampus ku tercinta</span>
    </div>
  </footer>

  <script>
    // Navbar scroll shadow
    window.addEventListener('scroll', () => {
      document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 10);
    });

    // Mobile menu
    function toggleMenu() {
      document.getElementById('mobileMenu').classList.toggle('open');
    }

    // Scroll reveal
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          observer.unobserve(e.target);
        }
      });
    }, {
      threshold: 0.12
    });
    reveals.forEach(r => observer.observe(r));
  </script>
</body>

</html>