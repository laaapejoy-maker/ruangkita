<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RuangKita – Booking Ruang Kampus</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
  <style>

  *{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Plus Jakarta Sans', sans-serif;
  }

  body{
    background:#f8fafc;
    overflow-x:hidden;
  }

  .dashboard-layout{
    display:flex;
    min-height:100vh;
  }

  .sidebar-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.35);
    z-index:998;
    opacity:0;
    visibility:hidden;
    transition:0.3s;
  }

  .sidebar-overlay.active{
    opacity:1;
    visibility:visible;
  }

  .sidebar{
    width:295px;
    background:#ffffff;
    padding:24px 18px;
    border-right:1px solid #e5e7eb;
    position:fixed;
    left:0;
    top:0;
    height:100vh;
    z-index:999;
    transition:0.3s ease;
    overflow-y:auto;
    transform:translateX(-100%);
  }

  .sidebar.active{
    transform:translateX(0);
  }

  .sidebar-logo{
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:28px;
  }

  .sidebar-logo img{
    width:48px;
    height:48px;
    object-fit:contain;
    border-radius:14px;
  }

  .sidebar-logo h2{
    font-size:20px;
    color:#111827;
    margin:0;
    font-weight:800;
  }

  .sidebar-logo p{
    font-size:13px;
    color:#6b7280;
    margin-top:3px;
  }

  .profile-box{
    background:#f8fafc;
    border:1px solid #e5e7eb;
    padding:18px;
    border-radius:20px;
    margin-bottom:30px;
  }

  .profile-name{
    font-size:15px;
    font-weight:700;
    color:#111827;
  }

  .profile-email{
    font-size:13px;
    color:#6b7280;
    margin-top:5px;
    word-break:break-word;
  }

  .sidebar-menu{
    display:flex;
    flex-direction:column;
    gap:10px;
  }

  .sidebar-menu a{
    text-decoration:none;
    display:flex;
    align-items:center;
    gap:14px;
    padding:15px 17px;
    border-radius:18px;
    color:#374151;
    font-weight:600;
    font-size:15px;
    transition:all 0.25s ease;
    position:relative;
    overflow:hidden;
  }

  .sidebar-menu a::before{
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(
      135deg,
      rgba(37,99,235,0.08),
      rgba(59,130,246,0.03)
    );
    opacity:0;
    transition:0.25s;
  }

  .sidebar-menu a:hover::before{
    opacity:1;
  }

  .sidebar-menu a:hover{
    transform:translateX(5px);
    color:#2563eb;
  }

  .sidebar-menu a.active{
    background:linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );
    color:white;
    box-shadow:
      0 12px 28px rgba(37,99,235,0.28);
  }

  .sidebar-menu a i{
    font-size:22px;
    min-width:22px;
    transition:
      transform 0.25s ease,
      color 0.25s ease;
    z-index:2;
  }

  .sidebar-menu a span{
    z-index:2;
  }

  .sidebar-menu a:hover i{
    transform:scale(1.15) rotate(-5deg);
    color:#2563eb;
  }

  .sidebar-menu a.active i{
    color:white;
  }

  .sidebar-bottom{
    margin-top:20px;
    padding-top:20px;
    border-top:1px solid #e5e7eb;
  }

  .sidebar-bottom .sidebar-menu a{
    color:#dc2626;
  }

  .sidebar-bottom .sidebar-menu a:hover{
    background:#fef2f2;
    color:#dc2626;
  }

  .sidebar-bottom .sidebar-menu a:hover i{
    color:#dc2626;
  }

  .main-content{
    width:100%;
    min-height:100vh;
  }

  .sidebar-toggle{
    position:fixed;
    top:18px;
    left:18px;
    z-index:1001;
    width:52px;
    height:52px;
    border:none;
    border-radius:18px;
    background:linear-gradient(
      135deg,
      #2563eb,
      #3b82f6
    );
    color:white;
    font-size:26px;
    cursor:pointer;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:0.25s ease;
    box-shadow:
      0 12px 28px rgba(37,99,235,0.25);
  }

  .sidebar-toggle:hover{
    transform:scale(1.05);
  }

  .sidebar-toggle.hide{
    opacity:0;
    visibility:hidden;
    pointer-events:none;
  }

  nav{
    width:100%;
    background:white;
    position:sticky;
    top:0;
    z-index:50;
    border-bottom:1px solid #e5e7eb;
    padding-left:90px;
  }

  .nav-inner{
    max-width:1400px;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:18px 40px;
  }

  .logo{
    display:flex;
    align-items:center;
    gap:10px;
    text-decoration:none;
    color:#111827;
    font-weight:800;
    font-size:20px;
  }

  .logo-icon img{
    width:30px;
    height:30px;
    object-fit:contain;
  }

  .nav-links{
    display:flex;
    gap:28px;
    list-style:none;
  }

  .nav-links a{
    text-decoration:none;
    color:#374151;
    font-weight:600;
    transition:0.2s;
  }

  .nav-links a:hover{
    color:#2563eb;
  }

  .hero{
    min-height:85vh;
    display:flex;
    align-items:center;
    padding:90px 80px;
  }

  .hero-content{
    max-width:760px;
  }

  .hero-badge{
    display:inline-flex;
    align-items:center;
    gap:10px;
    background:#dbeafe;
    color:#2563eb;
    padding:10px 16px;
    border-radius:999px;
    font-weight:700;
    font-size:14px;
    margin-bottom:24px;
  }

  .dot{
    width:8px;
    height:8px;
    border-radius:50%;
    background:#2563eb;
  }

  .hero h1{
    font-size:64px;
    line-height:1.1;
    color:#111827;
    margin-bottom:24px;
  }

  .hero h1 span{
    color:#2563eb;
  }

  .hero h1 em{
    font-style:normal;
    color:#1d4ed8;
  }

  .hero p{
    font-size:18px;
    line-height:1.8;
    color:#6b7280;
    margin-bottom:34px;
  }

  .hero-cta{
    display:flex;
    gap:16px;
    flex-wrap:wrap;
  }

  .btn-primary,
  .btn-outline{
    padding:15px 24px;
    border-radius:15px;
    text-decoration:none;
    font-weight:700;
    display:flex;
    align-items:center;
    gap:10px;
    transition:0.25s;
  }

  .btn-primary{
    background:#2563eb;
    color:white;
    box-shadow:0 10px 25px rgba(37,99,235,0.2);
  }

  .btn-primary:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
  }

  .btn-outline{
    border:1px solid #d1d5db;
    color:#111827;
    background:white;
  }

  .btn-outline:hover{
    background:#f3f4f6;
  }

  footer{
    background:#111827;
    color:white;
  }

  .footer-inner{
    max-width:1400px;
    margin:auto;
    padding:60px 40px;
  }

  .footer-brand p{
    margin-top:16px;
    color:#d1d5db;
    max-width:500px;
    line-height:1.8;
  }

  .footer-bottom{
    border-top:1px solid rgba(255,255,255,0.08);
    text-align:center;
    padding:18px;
    color:#d1d5db;
  }

  @media(max-width:900px){
    nav{
      padding-left:80px;
    }

    .nav-links{
      display:none;
    }

    .hero{
      padding:100px 30px 70px;
    }

    .hero h1{
      font-size:42px;
    }

    .hero p{
      font-size:16px;
    }

    .nav-inner{
      padding:18px 24px;
    }

    .sidebar{
      width:280px;
    }
  }
  </style>
</head>

<body>
<button class="sidebar-toggle"
        id="sidebarToggle"
        onclick="toggleSidebar()">
  ☰
</button>

<div class="sidebar-overlay"
     id="sidebarOverlay"
     onclick="closeSidebar()">
</div>

<div class="dashboard-layout">
  <div class="sidebar" id="sidebar">
    <div class="sidebar-logo">
      <img src="img/logo.png" alt="Logo">
      <div>
        <h2>RuangKita</h2>
        <p>User Panel</p>
      </div>
    </div>

    <div class="profile-box">
      <div class="profile-name">
        <?php echo $_SESSION['nama']; ?>
      </div>
      <div class="profile-email">
        <?php echo $_SESSION['email']; ?>
      </div>
    </div>

    <div class="sidebar-menu">
      <a href="user_dashboard.php"
         class="<?= ($current_page == 'user_dashboard.php') ? 'active' : ''; ?>">
        <i class="ri-dashboard-3-fill"></i>
        <span>Dashboard</span>
      </a>

      <a href="memilih_ruangan.php"
         class="<?= ($current_page == 'memilih_ruangan.php') ? 'active' : ''; ?>">
        <i class="ri-calendar-schedule-fill"></i>
        <span>Booking Ruangan</span>
      </a>

      <a href="kalender_jadwal_ruangan.php"
         class="<?= ($current_page == 'kalender_jadwal_ruangan.php') ? 'active' : ''; ?>">
        <i class="ri-calendar-check-fill"></i>
        <span>Kalender</span>
      </a>
    </div>

    <div class="sidebar-bottom">
      <div class="sidebar-menu">
        <a href="logout.php">
          <i class="ri-logout-circle-r-fill"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>
  </div>

  <div class="main-content">
    <nav id="navbar">
      <div class="nav-inner">
        <a href="#" class="logo">
          <div class="logo-icon">
            <img src="img/logo.png" alt="Logo">
          </div>
          RuangKita
        </a>

        <ul class="nav-links">
          <li><a href="#home">Home</a></li>
          <li><a href="#fitur">Fitur</a></li>
          <li><a href="#cara-kerja">Tentang</a></li>
          <li><a href="#kontak">Kontak</a></li>
        </ul>
      </div>
    </nav>

    <section id="home">
      <div class="hero">
        <div class="hero-content">
          <div class="hero-badge">
            <span class="dot"></span>
            Sistem Informasi Kampus
          </div>

          <h1>
            Solusi Booking Ruang Kampus yang
            <span>Mudah</span> dan
            <em>Cepat</em>
          </h1>

          <p>
            RuangKita memudahkan seluruh civitas akademika
            dalam mengajukan, mengelola, dan memantau
            peminjaman ruang kampus secara online.
          </p>

          <div class="hero-cta">
            <a href="memilih_ruangan.php" class="btn-primary">
              <i class="ri-calendar-check-fill"></i>
              Mulai Booking
            </a>

            <a href="#fitur" class="btn-outline">
              Pelajari Lebih Lanjut
            </a>
          </div>
        </div>
      </div>
    </section>
    <footer>

      <div class="footer-inner">
        <div class="footer-brand">
          <a href="#"
             class="logo"
             style="color:white;">
            <div class="logo-icon">
              <img src="img/logo.png" alt="Logo">
            </div>
            RuangKita
          </a>

          <p>
            Sistem informasi peminjaman ruang kampus
            yang modern, efisien, dan transparan.

          </p>

        </div>

      </div>

      <div class="footer-bottom">
        © 2025 RuangKita UPI PWK
      </div>

    </footer>

  </div>

</div>

<script>

window.addEventListener('scroll', () => {

  document.getElementById('navbar')
  .classList.toggle('scrolled', window.scrollY > 10);

});

const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const toggleBtn = document.getElementById('sidebarToggle');

function toggleSidebar(){

  sidebar.classList.toggle('active');
  overlay.classList.toggle('active');
  toggleBtn.classList.toggle('hide');

}

function closeSidebar(){
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
  toggleBtn.classList.remove('hide');
}
</script>
</body>
</html>